<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\Item;
use common\models\search\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Transaction;
use common\models\OrderItem;
use yii\data\ArrayDataProvider;
use yii\db\Expression;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $dataProvider->query
            ->orderBy(['id' => SORT_DESC])
            ->andWhere([
                'between',
                'created_at',
                date('Y-m-d 00:00:00'),
                date('Y-m-d 23:59:59')
            ]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Order();
        $items = new ActiveDataProvider([
            'query' => Item::find()->andWhere(['>', 'id', 0]),
        ]);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $postItems = Yii::$app->request->post('OrderItems', []);
                $transaction = Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);

                try {
                    // 1️⃣ Order base fields
                    $model->status = 'Дайындалуда';
                    $model->total = 0;

                    if (!$model->save(false)) {
                        throw new \Exception('Order not saved');
                    }

                    $total = 0;

                    // 2️⃣ Save order items
                    foreach ($postItems as $itemId => $data) {

                        $qty = (int)$data['quantity'];
                        if ($qty <= 0) {
                            continue;
                        }

                        $item = Item::findOne($itemId);
                        if (!$item) {
                            throw new \Exception("Item {$itemId} not found");
                        }

                        $orderItem = new OrderItem();
                        $orderItem->order_id = $model->id;
                        $orderItem->item_id  = $item->id;
                        $orderItem->quantity = $qty;

                        if (!$orderItem->save(false)) {
                            throw new \Exception('OrderItem not saved');
                        }

                        $total += $item->price * $qty;

                        $item->quantity -= $qty;
                        $item->save(false);
                    }

                    // 3️⃣ Update total
                    $model->total = $total;
                    $model->save(false);

                    $transaction->commit();

                    return $this->redirect(['index']);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    public function actionDone($id)
    {
        $order = $this->findModel($id);

        if ($order->status === 'Дайындалуда') {
            $order->status = 'Берілді';
        } elseif ($order->status === 'Берілді') {
            return $this->redirect(['checkout', 'id' => $order->id]);
        }

        $order->save(false);

        return $this->redirect(['index']);
    }



    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $items = new ActiveDataProvider([
            'query' => Item::find()->andWhere(['>', 'id', 0]),
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $postItems = Yii::$app->request->post('OrderItems', []);

            $transaction = Yii::$app->db->beginTransaction(\yii\db\Transaction::SERIALIZABLE);

            try {
                // 2️⃣ Prepare total
                $total = 0;

                // 3️⃣ Process each posted item
                foreach ($postItems as $itemId => $data) {
                    $qty = (int)$data['quantity'];

                    $item = Item::findOne($itemId);
                    if (!$item) {
                        throw new \Exception("Item {$itemId} not found");
                    }

                    // Find existing order item
                    $orderItem = OrderItem::findOne(['order_id' => $model->id, 'item_id' => $itemId]);
                    $oldQty = $orderItem ? $orderItem->quantity : 0;

                    if ($qty <= 0) {
                        // Remove existing item and restore stock
                        if ($orderItem) {
                            $item->quantity += $oldQty; // restore stock
                            $item->save(false);
                            $orderItem->delete();
                        }
                        continue;
                    }

                    if (!$orderItem) {
                        // Create new OrderItem
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $model->id;
                        $orderItem->item_id = $itemId;
                    }

                    // Update quantity
                    $orderItem->quantity = $qty;
                    if (!$orderItem->save(false)) {
                        throw new \Exception('Failed to save order item.');
                    }

                    // Adjust stock
                    $diff = $qty - $oldQty; // positive if added, negative if removed
                    $item->quantity -= $diff;
                    if ($item->quantity < 0) $item->quantity = 0; // safety
                    $item->save(false);

                    // Add to total
                    $total += $item->price * $qty;
                }

                // 4️⃣ Update order total
                $model->total = $total;
                if (!$model->save(false)) {
                    throw new \Exception('Failed to save order.');
                }

                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    public function actionCheckout($id)
    {
        $model = $this->findModel($id);

        $items = new ActiveDataProvider([
            'query' => Item::find()->andWhere(['<', 'id', 0]),
        ]);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $postItems = Yii::$app->request->post('OrderItems', []);

            $transaction = Yii::$app->db->beginTransaction(\yii\db\Transaction::SERIALIZABLE);

            try {
                $model->status = 'Төленді';

                // 2️⃣ Prepare total
                $total = 0;

                // 3️⃣ Process each posted item
                foreach ($postItems as $itemId => $data) {
                    $qty = (int)$data['quantity'];

                    $item = Item::findOne($itemId);
                    if (!$item) {
                        throw new \Exception("Item {$itemId} not found");
                    }

                    // Find existing order item
                    $orderItem = OrderItem::findOne(['order_id' => $model->id, 'item_id' => $itemId]);
                    $oldQty = $orderItem ? $orderItem->quantity : 0;

                    if ($qty <= 0) {
                        // Remove existing item and restore stock
                        if ($orderItem) {
                            $item->quantity += $oldQty; // restore stock
                            $item->save(false);
                            $orderItem->delete();
                        }
                        continue;
                    }

                    if (!$orderItem) {
                        // Create new OrderItem
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $model->id;
                        $orderItem->item_id = $itemId;
                    }

                    // Update quantity
                    $orderItem->quantity = $qty;
                    if (!$orderItem->save(false)) {
                        throw new \Exception('Failed to save order item.');
                    }

                    // Adjust stock
                    $diff = $qty - $oldQty; // positive if added, negative if removed
                    $item->quantity -= $diff;
                    if ($item->quantity < 0) $item->quantity = 0; // safety
                    $item->save(false);

                    // Add to total
                    $total += $item->price * $qty;
                }

                // 4️⃣ Update order total
                $model->total = $total;
                if (!$model->save(false)) {
                    throw new \Exception('Failed to save order.');
                }

                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('checkout', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    public function actionStats()
    {
        $query = Order::find()
            ->select([
                new \yii\db\Expression('DATE(created_at) AS day'),
                new \yii\db\Expression('SUM(total) AS money'),
                new \yii\db\Expression('COUNT(id) AS clients'),
            ])
            ->andWhere(['status' => 'Төленді'])
            ->groupBy(new \yii\db\Expression('DATE(created_at)'));

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'attributes' => [
                    'day' => [
                        'asc' => ['DATE(created_at)' => SORT_ASC],
                        'desc' => ['DATE(created_at)' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Күні',
                    ],
                    'money',
                    'clients',
                ],
                'defaultOrder' => ['day' => SORT_DESC],
            ],
        ]);

        return $this->render('stats', [
            'dataProvider' => $dataProvider,
        ]);
    }




    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
