<?php

use common\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Тапсырыстар';
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Жаңа тапсырыс', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-sm table-hover table-striped'
        ],
        'summary' => 'Тапсырыс саны: {totalCount}',
        'emptyText' => 'Ештеңе жоқ!',
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
        ],
        'columns' => [
            [
                'attribute' => 'key_number',
                'label' => 'Кілт',
                'headerOptions' => ['style' => 'width: 10%']
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
            ],
            [
                'attribute' => 'total',
                'label' => 'Төлем',
                'headerOptions' => ['style' => 'width: 10%']
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>