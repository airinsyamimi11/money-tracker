<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $p */

$this->title = 'My Plan';
$rm = fn($n) => 'RM ' . number_format((float) $n, 2);

$messages = [
    'noincome' => ['', 'Add your salary or pocket money first, then I can work out what you can safely spend.'],
    'nodata'   => ['', 'No spending recorded yet, so this plan uses your income only. Add expenses and it becomes more accurate.'],
    'ok'       => ['good', 'Good news: you usually spend ' . $rm($p['avgSpend']) . ' a month (' . $p['basis'] . '), which fits this plan.'],
    'over'     => ['warn', 'You usually spend ' . $rm($p['avgSpend']) . ' a month (' . $p['basis'] . '), which is ' . $rm($p['gap']) . ' more than this plan allows.'],
    'broke'    => ['bad', 'Warning: you usually spend ' . $rm($p['avgSpend']) . ' a month (' . $p['basis'] . '), more than the ' . $rm($p['income']) . ' you earn.'],
];
[$cls, $msg] = $messages[$p['status']];
?>

<div class="wt-header">
    <h1>Your Spending Plan</h1>
    <div><?= Html::a('Edit income', ['income/index'], ['class' => 'btn-wt-outline']) ?></div>
</div>

<div class="wt-status <?= $cls ?>"><?= Html::encode($msg) ?></div>

<?php if ($p['income'] > 0): ?>

    <div class="wt-seg">
        <span>I want to save:</span>
        <?php foreach ([10, 20, 30, 40] as $pct): ?>
            <?= Html::a($pct . '%', ['plan/index', 'save' => $pct], ['class' => $pct === $p['savePct'] ? 'active' : '']) ?>
        <?php endforeach; ?>
    </div>

    <div class="wt-hero">
        <div class="label">Safe to spend per month</div>
        <div class="amount"><?= $rm($p['monthly']) ?></div>
        <div class="sub">
            <span>Income <?= $rm($p['income']) ?> per month</span>
            <span>Saved <?= $rm($p['saving']) ?> (<?= $p['savePct'] ?>%)</span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach (
            [
                ['Per day', $p['daily'], 'Your daily limit'],
                ['Per week', $p['weekly'], 'Your weekly limit'],
                ['Per month', $p['monthly'], 'Your monthly limit'],
                ['Per year', $p['yearly'], 'Total for 12 months'],
            ] as [$label, $value, $hint]
        ): ?>
            <div class="col-6 col-md-3">
                <div class="wt-card">
                    <div class="label"><?= $label ?></div>
                    <div class="value"><?= $rm($value) ?></div>
                    <div class="hint"><?= $hint ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="wt-panel">
                <h4>Needs, wants and savings</h4>
                <?php foreach (
                    [
                        ['Needs (food, transport, bills, health)', $p['needsAvg'], $p['needsCap']],
                        ['Wants (shopping, fun, other)', $p['wantsAvg'], $p['wantsCap']],
                    ] as [$label, $avg, $cap]
                ):
                    $width = $cap > 0 ? min(100, $avg / $cap * 100) : ($avg > 0 ? 100 : 0); ?>
                    <div class="wt-cat">
                        <div class="row-top">
                            <span><?= $label ?></span>
                            <span><?= $rm($avg) ?> of <?= $rm($cap) ?></span>
                        </div>
                        <div class="wt-bar <?= $avg > $cap ? 'over' : '' ?>"><span style="width:<?= round($width) ?>%"></span></div>
                    </div>
                <?php endforeach; ?>
                <div class="wt-cat mb-0">
                    <div class="row-top"><span>Savings goal</span><span><?= $rm($p['saving']) ?> per month</span></div>
                </div>
                <div class="hint text-muted small mt-3">
                    Left number = what you usually spend. Right number = suggested limit.
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="wt-panel">
                <h4>This month so far</h4>
                <div class="wt-cat">
                    <div class="row-top"><span>Spent</span><span><?= $rm($p['spentNow']) ?></span></div>
                </div>
                <div class="wt-cat">
                    <div class="row-top"><span>Left to spend</span><span><?= $rm(max(0, $p['left'])) ?></span></div>
                </div>
                <div class="wt-cat">
                    <div class="row-top"><span>You can spend per day from now</span><span><?= $rm($p['dailyLeft']) ?></span></div>
                </div>
                <?= Html::beginForm(['plan/apply', 'save' => $p['savePct']], 'post') ?>
                <?= Html::submitButton('Use this as my budget for ' . date('F Y'), ['class' => 'btn-wt mt-2']) ?>
                <?= Html::endForm() ?>
                <div class="text-muted small mt-2">This fills in your Budget page, so the Dashboard uses these numbers.</div>
            </div>
        </div>
    </div>

    <div class="wt-panel mb-5">
        <h4>How to stay out of the red</h4>
        <?php foreach ($p['tips'] as $tip): ?>
            <div class="wt-tip"><span><?= Html::encode($tip) ?></span></div>
        <?php endforeach; ?>
    </div>

<?php else: ?>

    <div class="wt-panel mb-5">
        <h4>Start with your income</h4>
        <p>Add your salary or pocket money once, and this page works out what you can safely spend each day, week, month and year.</p>
        <?= Html::a('+ Add income', ['income/create'], ['class' => 'btn-wt']) ?>
    </div>

<?php endif; ?>