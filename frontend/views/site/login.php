<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Жуйеге кіру';
?>
<div class="site-login">

    <div class="d-flex justify-content-center mt-5">
        <div class="card p-3 shadow-sm text-center" style="width: 300px;">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Аккаунт'])->label(false) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Құпия сөз'])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton('Жуйеге кіру', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
