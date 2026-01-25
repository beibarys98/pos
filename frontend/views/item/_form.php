<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'autofocus' => true, 'placeholder' => 'Атауы'])->label(false) ?>

    <?= $form->field($model, 'price')->input('number', ['placeholder' => 'Бағасы'])->label(false) ?>

    <?= $form->field($model, 'quantity')->input('number', ['placeholder' => 'Саны'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сақтау', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>