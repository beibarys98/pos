<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Order $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Өзгерту: ' . $model->key_number;
?>

<div class="order-form">

    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="mb-3"></div>

    <?= $form->field($model, 'key_number')->input('number', ['maxlength' => true, 'autofocus' => true, 'placeholder' => 'Кілт'])->label(false) ?>

    <div class="mb-3"></div>

    <?= GridView::widget([
        'dataProvider' => $items,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-sm table-hover table-striped'],
        'columns' => [
            [
                'attribute' => 'title',
                'label' => 'Атауы',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'price',
                'contentOptions' => ['class' => 'text-center', 'style' => 'width: 10%'],
                'label' => 'Бағасы',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'quantity',
                'contentOptions' => ['class' => 'text-center', 'style' => 'width: 10%'],
                'label' => 'Қалды',
                'enableSorting' => false,
                'format' => 'raw',
                'value' => function ($item) {
                    // Just show current stock from DB
                    return '
                        <span id="stock-' . $item->id . '">' . $item->quantity . '</span>
                        <input type="hidden" id="stock-init-' . $item->id . '" value="' . $item->quantity . '">
                    ';
                }

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
    const stockSpan = document.getElementById('stock-' + id);

    let buy = parseInt(buySpan.innerText);
    let stock = parseInt(stockSpan.innerText);

    if (delta > 0) {
        if (stock <= 0) return; // cannot exceed stock
        buy++;
        stock--;
    } else {
        if (buy <= 0) return; // cannot go below 0
        buy--;
        stock++;
    }

    buySpan.innerText = buy;
    buyInput.value = buy;
    stockSpan.innerText = stock;

    recalcTotal();
}

window.recalcTotal = function() {
    let total = 0;

    const items = document.querySelectorAll('input[id^="input-"]');
    items.forEach(function(input) {
        const id = input.id.replace('input-', '');
        const qty = parseInt(input.value);
        if (qty > 0) {
            const priceCell = input.closest('tr').querySelector('td:nth-child(2)');
            const price = parseInt(priceCell.innerText);
            total += price * qty;
        }
    });

    document.getElementById('order-total').innerText = total;
}

window.recalcTotal();

JS);
?>