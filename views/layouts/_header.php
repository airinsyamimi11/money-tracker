<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$loggedIn = !Yii::$app->user->isGuest;

$items = [
    [
        'label' => 'Dashboard',
        'url' => ['/site/index'], 'visible' => $loggedIn,
    ],
    [
        'label' => 'My Plan',
        'url' => ['/plan/index'], 'visible' => $loggedIn,
    ],
    [
        'label' => 'Income',
        'url' => ['/income/index'], 'visible' => $loggedIn,
    ],
    [
        'label' => 'Expenses',
        'url' => ['/expense/index'], 'visible' => $loggedIn,
    ],
    [
        'label' => 'Budget',
        'url' => ['/budget/index'], 'visible' => $loggedIn,
    ],
];

?>
<header id="header">
    <?php NavBar::begin(
        [
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ],
    ) ?>
    <?= Nav::widget(
        [
            'options' => ['class' => 'navbar-nav me-auto'],
            'encodeLabels' => false,
            'items' => $items,
        ],
    ) ?>

    <?php if ($loggedIn): ?>
        <div class="d-flex align-items-center mt-2 mt-md-0">
            <span class="nav-user">Hi, <? Html::encode(Yii::$app->user->identity->username) ?></span>
            <?= Html::beginForm(['/site/logout'], 'post') ?>
            <?= Html::submitButton('Logout', ['class' => 'btn-logout']) ?>
            <?= Html::endForm() ?>
        </div>
    <?php endif; ?>
    <?php NavBar::end() ?>
</header>