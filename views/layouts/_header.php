<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$items = [
    [
        'label' => 'Dashboard',
        'url' => ['/site/index'],
    ],
    [
        'label' => 'My Plan',
        'url' => ['/plan/index'],
    ],
    [
        'label' => 'Income',
        'url' => ['/income/index'],
    ],
    [
        'label' => 'Expenses',
        'url' => ['/expense/index'],
    ],
    [
        'label' => 'Budget',
        'url' => ['/budget/index'],
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
    <?php NavBar::end() ?>
</header>