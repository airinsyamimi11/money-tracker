<?php

use app\models\Expense;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Expenses';
?>
<div class="wt-header">
    <h1>Your Expenses</h1>
    <div>
        <?= Html::a('+ Add expense', ['create'], ['class' => 'btn-wt']) ?>
    </div>
</div>

<div class="wt-panel mb-5">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n<div class=\"mt-3\">{pager}</div>",
        'tableOptions' => ['class' => 'table table-hover align-middle wt-table'],
        'emptyText' => 'No expenses yet. Tap “+ Add expense” to record your first one.',
        'emptyTextOptions' => ['class' => 'wt-empty'],
        'pager' => ['class' => LinkPager::class],
        'columns' => [
            [
                'attribute' => 'spent_on',
                'label' => 'Date',
                'value' => fn(Expense $m) => date('d M Y', strtotime($m->spent_on)),
            ],
            [
                'attribute' => 'title',
                'label' => 'Description',
            ],
            [
                'attribute' => 'category',
                'format' => 'raw',
                'value' => fn(Expense $m) => Html::tag('span', Html::encode($m->category), ['class' => 'wt-badge']),
            ],
            [
                'attribute' => 'amount',
                'label' => 'Amount',
                'contentOptions' => ['class' => 'text-end fw-bold'],
                'headerOptions' => ['class' => 'text-end'],
                'value' => fn(Expense $m) => 'RM ' . number_format((float) $m->amount, 2),
            ],
            [
                'class' => ActionColumn::class,
                'header' => '',
                'template' => '{update} {delete}',
                'contentOptions' => ['class' => 'text-end text-nowrap'],
                'urlCreator' => fn($action, Expense $m) => Url::toRoute([$action, 'id' => $m->id]),
                'buttons' => [
                    'update' => fn($url) => Html::a('Edit', $url, ['class' => 'wt-btn-sm']),
                    'delete' => fn($url) => Html::a('Delete', $url, [
                        'class' => 'wt-btn-sm danger',
                        'data' => ['confirm' => 'Delete this expense?', 'method' => 'post'],
                    ]),
                ],
            ],
        ],
    ]); ?>
</div>