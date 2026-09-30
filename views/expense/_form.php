<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expense $model */

if (!$model->spent_on) {
    $model->spent_on = date('Y-m-d');
}

$categories = [
    'Food'      => 'Food & Groceries',
    'Transport' => 'Transport',
    'Bills'     => 'Bills & Utilities',
    'Shopping'  => 'Shopping',
    'Health'    => 'Health',
    'Fun'       => 'Fun & Entertainment',
    'Other'     => 'Other',
];
?>

<div class="wt-panel wt-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')
        ->textInput(['maxlength' => true, 'placeholder' => 'e.g. Lunch at the mall', 'autofocus' => true])
        ->label('What did you spend on?') ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'amount')
                ->input('number', ['step' => '0.01', 'min' => '0.01', 'placeholder' => '0.00'])
                ->label('Amount (RM)') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'spent_on')
                ->input('date')
                ->label('Date') ?>
        </div>
    </div>

    <?= $form->field($model, 'category')
        ->dropDownList($categories, ['prompt' => 'Choose a category...'])
        ->label('Category') ?>

    <div class="mt-4">
        <?= Html::submitButton('Save expense', ['class' => 'btn-wt']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn-wt-outline ms-1']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>