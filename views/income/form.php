<?php

use app\models\Income;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Income $model */
/** @var string $heading */

$this->title = $heading;
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wt-header">
    <h1><?= Html::encode($heading) ?></h1>
</div>

<div class="wt-panel wt-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')
        ->textInput(['maxlength' => true, 'placeholder' => 'e.g. Salary, Pocket money, Part-time job', 'autofocus' => true]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'amount')
                ->input('number', ['step' => '0.01', 'min' => '0.01', 'placeholder' => '0.00']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'frequency')->dropDownList(Income::FREQ) ?>
        </div>
    </div>

    <div class="mt-4">
        <?= Html::submitButton('Save income', ['class' => 'btn-wt']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn-wt-outline ms-1']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>