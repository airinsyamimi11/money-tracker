<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expense $model */

$this->title = 'Edit Expense';
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-update">

    <div class="wt-header"><h1><?= Html::encode($this->title) ?></h1></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>