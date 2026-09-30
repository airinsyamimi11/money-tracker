<?php

use app\models\Income;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Income';
$rm = fn($n) => 'RM ' . number_format((float) $n, 2);
?>
<div class="wt-header">
    <h1>Your Income</h1>
    <div>
        <?= Html::a('+ Add income', ['create'], ['class' => 'btn-wt']) ?>
        <?= Html::a('See my plan →', ['plan/index'], ['class' => 'btn-wt-outline ms-1']) ?>
    </div>
</div>

<div class="wt-panel mb-3">
    <div class="wt-empty pt-0 pb-2">Total per month: <strong><?= $rm(Income::monthlyTotal()) ?></strong></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items}',
        'tableOptions' => ['class' => 'table table-hover align-middle wt-table'],
        'emptyText' => 'No income yet. Tap “+ Add income” to add your salary or pocket money.',
        'emptyTextOptions' => ['class' => 'wt-empty'],
        'columns' => [
            ['attribute' => 'title', 'label' => 'Source'],
            [
                'attribute' => 'frequency',
                'label' => 'How often',
                'value' => fn(Income $m) => Income::FREQ[$m->frequency] ?? $m->frequency,
            ],
            [
                'attribute' => 'amount',
                'label' => 'Amount',
                'contentOptions' => ['class' => 'text-end'],
                'headerOptions' => ['class' => 'text-end'],
                'value' => fn(Income $m) => $rm($m->amount),
            ],
            [
                'label' => 'Per month',
                'contentOptions' => ['class' => 'text-end fw-bold'],
                'headerOptions' => ['class' => 'text-end'],
                'value' => fn(Income $m) => $rm($m->monthlyAmount()),
            ],
            [
                'class' => ActionColumn::class,
                'header' => '',
                'template' => '{update} {delete}',
                'contentOptions' => ['class' => 'text-end text-nowrap'],
                'urlCreator' => fn($action, Income $m) => Url::toRoute([$action, 'id' => $m->id]),
                'buttons' => [
                    'update' => fn($url) => Html::a('Edit', $url, ['class' => 'wt-btn-sm']),
                    'delete' => fn($url) => Html::a('Delete', $url, [
                        'class' => 'wt-btn-sm danger',
                        'data' => ['confirm' => 'Delete this income?', 'method' => 'post'],
                    ]),
                ],
            ],
        ],
    ]); ?>
</div>