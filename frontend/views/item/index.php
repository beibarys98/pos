<?php

use common\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\ItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Меню';
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Жаңа тағам', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-sm table-hover table-striped'
        ],
        'summary' => 'Тағам саны: {totalCount}',
        'emptyText' => 'Ештеңе жоқ!',
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
        ],
        'columns' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы',
            ],
            [
                'attribute' => 'price',
                'label' => 'Бағасы',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Саны',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} <span style="margin-right: 10px;"></span> {delete}',
                'urlCreator' => function ($action, Item $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <br>
    <br>
    <br>

    <h1>Кіру бағасы</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'tableOptions' => [
            'class' => 'table table-sm table-hover table-striped'
        ],
        'summary' => false,
        'emptyText' => 'Ештеңе жоқ!',
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
        ],
        'columns' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы',
            ],
            [
                'attribute' => 'price',
                'label' => 'Бағасы',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} <span style="margin-right: 10px;"></span> {delete}',
                'urlCreator' => function ($action, Item $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>