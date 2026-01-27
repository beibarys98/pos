<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use common\models\OrderItem;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Төлем жасау';
?>

<div class="order-form">

    <h1><?= $this->title ?></h1>

    <?= GridView::widget([
        'dataProvider' => $items,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-sm table-hover table-striped'],
        'columns' => [
            [
                'attribute' => 'item_id',
                'label' => 'Атауы',
                'enableSorting' => false,
                'value' => function ($item) {
                    return $item->item->title;
                }
            ],
            [
                'attribute' => 'price',
                'label' => 'Бағасы',
                'enableSorting' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'value' => function ($item) {
                    return $item->item->price;
                }
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Саны',
                'enableSorting' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
        ],
    ]); ?>

    <br>
    <br>
    <br>

    <?php $form = ActiveForm::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $items2,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-sm table-hover table-striped additional-services'],
        'columns' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'price',
                'label' => 'Бағасы',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'enableSorting' => false,
            ],
            [
                'headerOptions' => ['style' => 'width: 10%'],
                'label' => 'Тапсырыс',
                'format' => 'raw',
                'value' => function ($item) use ($model) {
                    $orderItem = \common\models\OrderItem::findOne(['order_id' => $model->id, 'item_id' => $item->id]);
                    $qty = $orderItem ? $orderItem->quantity : 0;

                    return '
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            onclick="changeQty(' . $item->id . ', -1)">−</button>

                        <span id="qty-' . $item->id . '">' . $qty . '</span>

                        <button type="button" class="btn btn-sm btn-outline-success"
                            onclick="changeQty(' . $item->id . ', 1)">+</button>

                        <input type="hidden"
                            name="OrderItems[' . $item->id . '][quantity]"
                            id="input-' . $item->id . '"
                            value="' . $qty . '">
                    </div>';
                }
            ],
        ],
    ]); ?>


    <div class="text-center my-3">
        <div class="fs-5 fw-bold">
            Барлығы:
            <br>
            <span id="order-total" class="text-success">0</span> <span class="text-success">₸</span>
        </div>
    </div>


    <div class="form-group text-center">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
window.changeQty = function(id, delta) {
    const buySpan = document.getElementById('qty-' + id);
    const buyInput = document.getElementById('input-' + id);

    let buy = parseInt(buySpan.innerText);

    if (delta > 0) {
        buy++;
    } else {
        if (buy <= 0) return; // cannot go below 0
        buy--;
    }

    buySpan.innerText = buy;
    buyInput.value = buy;

    recalcTotal();
}

window.recalcTotal = function() {
    let total = 0;

    // loop through each row of the GridView
    const rows = document.querySelectorAll('.table tbody tr');
    rows.forEach(function(row) {
        // get price cell (2nd column)
        let priceCell = row.querySelector('td:nth-child(2)');
        let qtyCell = row.querySelector('td:nth-child(3)');

        if (!priceCell || !qtyCell) return;

        // parse numbers
        let price = parseFloat(priceCell.innerText.replace(/\D/g,'')) || 0;
        let qty = parseInt(qtyCell.innerText.replace(/\D/g,'')) || 0;

        total += price * qty;
    });

    // update total in DOM
    document.getElementById('order-total').innerText = total;
}

// run on page load
window.recalcTotal();


JS);
?>