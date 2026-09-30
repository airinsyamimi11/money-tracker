<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Budget $model */

$this->title = 'Edit Budget';
$this->params['breadcrumbs'][] = ['label' => 'Budget', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-update">

    <div class="wt-header"><h1><?= Html::encode($this->title) ?></h1></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>