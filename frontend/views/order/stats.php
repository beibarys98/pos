<?php

use yii\grid\GridView;

/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Статистика';
?>

<h3><?= $this->title ?></h3>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'tableOptions' => ['class' => 'table table-sm table-striped'],
    'columns' => [
        [
            'attribute' => 'day',
            'label' => 'Күн',
            'value' => function ($model) {
                return date('d/m/Y', strtotime($model['day']));
            },

        ],
        [
            'label' => 'Кілттер',
            'attribute' => 'clients',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
        ],
        [
            'attribute' => 'money',
            'label' => 'Ақша',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'format' => 'raw',
            'value' => function ($model) {
                return number_format($model['money'], 0, '.', ' ') . ' ₸';
            },
        ],
    ],
]); ?>