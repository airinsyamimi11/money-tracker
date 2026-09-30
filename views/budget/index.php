<?php

use app\models\Budget;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Monthly Budget';
?>
<div class="wt-header">
    <h1>Monthly Budget</h1>
    <div>
        <?= Html::a('+ Set budget', ['create'], ['class' => 'btn-wt']) ?>
    </div>
</div>

<div class="wt-panel mb-5">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n<div class=\"mt-3\">{pager}</div>",
        'tableOptions' => ['class' => 'table table-hover align-middle wt-table'],
        'emptyText' => 'No budget yet. Tap “+ Set budget” to plan your first month.',
        'emptyTextOptions' => ['class' => 'wt-empty'],
        'pager' => ['class' => LinkPager::class],
        'columns' => [
            [
                'attribute' => 'month',
                'label' => 'Month',
                'value' => fn(Budget $m) => date('F Y', strtotime($m->month . '-01')),
            ],
            [
                'attribute' => 'amount',
                'label' => 'Budget',
                'contentOptions' => ['class' => 'text-end fw-bold'],
                'headerOptions' => ['class' => 'text-end'],
                'value' => fn(Budget $m) => 'RM ' . number_format((float) $m->amount, 2),
            ],
            [
                'class' => ActionColumn::class,
                'header' => '',
                'template' => '{update} {delete}',
                'contentOptions' => ['class' => 'text-end text-nowrap'],
                'urlCreator' => fn($action, Budget $m) => Url::toRoute([$action, 'id' => $m->id]),
                'buttons' => [
                    'update' => fn($url) => Html::a('Edit', $url, ['class' => 'wt-btn-sm']),
                    'delete' => fn($url) => Html::a('Delete', $url, [
                        'class' => 'wt-btn-sm danger',
                        'data' => ['confirm' => 'Delete this budget?', 'method' => 'post'],
                    ]),
                ],
            ],
        ],
    ]); ?>
</div>