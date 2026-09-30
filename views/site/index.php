<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $s */

$this->title = 'Dashboard';
$rm = fn($n) => 'RM ' . number_format((float) $n, 2);

$ts   = strtotime($s['month'] . '-01');
$prev = date('Y-m', strtotime('-1 month', $ts));
$next = date('Y-m', strtotime('+1 month', $ts));

$hasBudget = $s['budget'] > 0;
$over      = $hasBudget && $s['spent'] > $s['budget'];
$risk      = $hasBudget && !$over && $s['projected'] > $s['budget'];
$pct       = $hasBudget ? min(100, $s['spent'] / $s['budget'] * 100) : 0;

if (!$hasBudget) {
    [$cls, $msg] = ['', 'Welcome! Start by setting your monthly budget, then add your expenses.'];
} elseif ($over) {
    [$cls, $msg] = ['bad', 'You are over budget by ' . $rm($s['spent'] - $s['budget']) . '. Try to pause non-essential spending.'];
} elseif ($risk) {
    [$cls, $msg] = ['warn', 'Heads up: at this pace you may overspend by ' . $rm($s['projected'] - $s['budget']) . ' this month.'];
} else {
    [$cls, $msg] = ['good', 'You are on track. Keep it up!'];
}
?>

<div class="wt-header">
    <h1>Your Money</h1>
    <div class="wt-month">
        <?= Html::a('‹', ['site/index', 'month' => $prev], ['title' => 'Previous month']) ?>
        <span><?= date('F Y', $ts) ?></span>
        <?= Html::a('›', ['site/index', 'month' => $next], ['title' => 'Next month']) ?>
    </div>
    <div>
        <?= Html::a('+ Add expense', ['expense/create'], ['class' => 'btn-wt']) ?>
        <?= Html::a('Monthly budget', ['budget/index'], ['class' => 'btn-wt-outline ms-1']) ?>
    </div>
</div>

<div class="wt-status <?= $cls ?>"><?= Html::encode($msg) ?></div>

<div class="wt-hero">
    <div class="label">Left to spend this month</div>
    <div class="amount"><?= $rm(max(0, $s['remaining'])) ?></div>
    <div class="wt-bar <?= $over ? 'over' : '' ?>"><span style="width:<?= round($pct) ?>%"></span></div>
    <div class="sub">
        <span><?= $rm($s['spent']) ?> spent</span>
        <span>of <?= $rm($s['budget']) ?> budget</span>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="wt-card">
            <div class="label">You can spend per day</div>
            <div class="value"><?= $rm($s['daily']) ?></div>
            <div class="hint">For the rest of this month</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="wt-card">
            <div class="label">You can spend per week</div>
            <div class="value"><?= $rm($s['weekly']) ?></div>
            <div class="hint">Daily amount × 7</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="wt-card">
            <div class="label">Expected total by month-end</div>
            <div class="value"><?= $rm($s['projected']) ?></div>
            <div class="hint">If you keep spending at this pace</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-lg-6">
        <div class="wt-panel">
            <h4>Where your money goes</h4>
            <?php if (!$s['byCategory']): ?>
                <div class="wt-empty">No expenses yet this month. Tap “+ Add expense” to record your first one.</div>
            <?php endif; ?>
            <?php foreach ($s['byCategory'] as $c):
                $share = $s['spent'] > 0 ? $c['total'] / $s['spent'] * 100 : 0; ?>
                <div class="wt-cat">
                    <div class="row-top">
                        <span><?= Html::encode($c['category']) ?></span>
                        <span><?= $rm($c['total']) ?> · <?= round($share) ?>%</span>
                    </div>
                    <div class="wt-bar"><span style="width:<?= round($share) ?>%"></span></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="wt-panel">
            <h4>Saving tips for you</h4>
            <?php foreach ($s['tips'] as $t): ?>
                <div class="wt-tip"><span><?= Html::encode($t) ?></span></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>