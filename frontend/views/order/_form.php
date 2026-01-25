<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'key_number')->input('number', ['maxlength' => true, 'autofocus' => true, 'placeholder' => 'Кілт'])->label(false) ?>

    <?php
    $dataProvider = new ArrayDataProvider([
        'allModels' => $items,
        'pagination' => false,
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'showHeader' => false,
        'tableOptions' => ['class' => 'table table-sm table-hover table-striped'],
        'columns' => [
            'title',
            [
                'attribute' => 'price',
            ],
            [
                'format' => 'raw',
                'value' => function ($item) {
                    return '
                        <span id="stock-' . $item->id . '">' . $item->quantity . '</span>
                        <input type="hidden"
                            id="stock-init-' . $item->id . '"
                            value="' . $item->quantity . '">
                    ';
                }
            ],

            [
                'label' => 'Buy',
                'format' => 'raw',
                'value' => function ($item) {
                    return '
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="changeQty(' . $item->id . ', -1)">−</button>

                            <span id="qty-' . $item->id . '">0</span>

                            <button type="button" class="btn btn-sm btn-outline-success"
                                onclick="changeQty(' . $item->id . ', 1)">+</button>

                            <input type="hidden"
                                name="OrderItems[' . $item->id . '][quantity]"
                                id="input-' . $item->id . '"
                                value="0">
                        </div>';
                }
            ],
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
window.changeQty = function(id, delta) {
    const buySpan = document.getElementById('qty-' + id);
    const buyInput = document.getElementById('input-' + id);
    const stockSpan = document.getElementById('stock-' + id);

    let buy = parseInt(buySpan.innerText);
    let stock = parseInt(stockSpan.innerText);

    if (delta > 0) {
        if (stock <= 0) return;
        buy++;
        stock--;
    } else {
        if (buy <= 0) return;
        buy--;
        stock++;
    }

    buySpan.innerText = buy;
    buyInput.value = buy;
    stockSpan.innerText = stock;
}
JS);
?>