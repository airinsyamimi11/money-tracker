<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Budget $model */

if (!$model->month) {
    $model->month = date('Y-m');
}
?>

<div class="wt-panel wt-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'month')
        ->input('month')
        ->label('Which month?')
        ->hint('Pick the month this budget is for. Each month can only have one budget.') ?>

    <?= $form->field($model, 'amount')
        ->input('number', ['step' => '0.01', 'min' => '0.01', 'placeholder' => '0.00'])
        ->label('Total budget (RM)')
        ->hint('The most you plan to spend in this month.') ?>

    <div class="mt-4">
        <?= Html::submitButton('Save budget', ['class' => 'btn-wt']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn-wt-outline ms-1']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>