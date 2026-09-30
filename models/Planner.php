<?php

namespace app\models;

use DateTimeImmutable;

class Planner
{
    /** Categories counted as "needs". Everything else counts as "wants". */
    public const NEEDS = ['Food', 'Transport', 'Bills', 'Health'];

    public static function build(int $savePct = 20): array
    {
        $savePct = max(0, min(40, $savePct));

        // 1. Income and the plan
        $income = Income::monthlyTotal();
        $saving = $income * $savePct / 100;
        $spendable = $income - $saving;
        $needsCap = $income * 0.50;
        $wantsCap = max(0, $spendable - $needsCap);

        // 2. What you really spend: average of the last 3 full months
        $firstThis = new DateTimeImmutable('first day of this month');
        $from = $firstThis->modify('-3 months')->format('Y-m-d');
        $to = $firstThis->modify('-1 day')->format('Y-m-d');

        $rows = Expense::find()
            ->select(['ym' => "DATE_FORMAT(spent_on, '%Y-%m')", 'category', 'total' => 'SUM(amount)'])
            ->where(['between', 'spent_on', $from, $to])
            ->groupBy(['ym', 'category'])
            ->asArray()->all();

        $months = [];
        $byCat = [];
        foreach ($rows as $r) {
            $months[$r['ym']] = true;
            $byCat[$r['category']] = ($byCat[$r['category']] ?? 0) + (float) $r['total'];
        }
        $monthCount = count($months);

        if ($monthCount > 0) {
            $avgByCat = array_map(fn($t) => $t / $monthCount, $byCat);
            $basis = $monthCount === 1 ? 'last month' : "the last $monthCount months";
        } else {
            // No full month yet: estimate from this month so far
            $day = max(1, (int) date('j'));
            $daysInMonth = (int) date('t');
            $rows = Expense::find()
                ->select(['category', 'total' => 'SUM(amount)'])
                ->where(['between', 'spent_on', $firstThis->format('Y-m-d'), date('Y-m-d')])
                ->groupBy('category')
                ->asArray()->all();
            $avgByCat = [];
            foreach ($rows as $r) {
                $avgByCat[$r['category']] = (float) $r['total'] / $day * $daysInMonth;
            }
            $basis = 'this month so far';
        }

        $avgSpend = array_sum($avgByCat);
        $needsAvg = 0.0;
        $wantsAvg = 0.0;
        foreach ($avgByCat as $cat => $total) {
            if (in_array($cat, self::NEEDS, true)) {
                $needsAvg += $total;
            } else {
                $wantsAvg += $total;
            }
        }

        // 3. Status
        if ($income <= 0) {
            $status = 'noincome';
        } elseif ($avgSpend <= 0) {
            $status = 'nodata';
        } elseif ($avgSpend > $income) {
            $status = 'broke';
        } elseif ($avgSpend > $spendable) {
            $status = 'over';
        } else {
            $status = 'ok';
        }
        $gap = max(0, $avgSpend - $spendable);

        // 4. This month so far
        $spentNow = (float) Expense::find()
            ->where(['between', 'spent_on', $firstThis->format('Y-m-d'), date('Y-m-t')])
            ->sum('amount');
        $daysLeft = (int) date('t') - (int) date('j') + 1;
        $left = $spendable - $spentNow;
        $dailyLeft = $left > 0 ? $left / $daysLeft : 0;

        // 5. Emergency fund: 3 months of essentials
        $emergency = 3 * ($needsAvg > 0 ? $needsAvg : $needsCap);

        // 6. Tips
        $tips = [];
        if ($status === 'broke') {
            $tips[] = 'You are spending about RM ' . number_format($avgSpend - $income, 2)
                . ' more than you earn each month. Cut "wants" first, then review your biggest needs.';
        } elseif ($status === 'over') {
            $tips[] = 'To fit this plan, trim about RM ' . number_format($gap, 2)
                . ' a month. The easiest place is "wants" (Shopping, Fun, Other).';
        }
        if ($wantsCap > 0 && $wantsAvg > $wantsCap) {
            $tips[] = 'Your "wants" are RM ' . number_format($wantsAvg - $wantsCap, 2)
                . ' above the suggested limit. Try a 24-hour wait before non-essential purchases.';
        }
        if ($needsAvg > $needsCap && $income > 0) {
            $tips[] = 'Your "needs" take more than half of your income. Look at bills and subscriptions you can lower.';
        }
        if ($income > 0) {
            $tips[] = 'Move your savings (RM ' . number_format($saving, 2)
                . ') out on payday, so you only spend what is left.';
            $tips[] = 'Build an emergency fund of about RM ' . number_format($emergency, 2)
                . ' (3 months of essentials) before spending more on wants.';
        }

        return [
            'status' => $status,
            'savePct' => $savePct,
            'income' => $income,
            'saving' => $saving,
            'monthly' => $spendable,
            'yearly' => $spendable * 12,
            'weekly' => $spendable * 12 / 52,
            'daily' => $spendable * 12 / 365,
            'needsCap' => $needsCap,
            'wantsCap' => $wantsCap,
            'needsAvg' => $needsAvg,
            'wantsAvg' => $wantsAvg,
            'avgSpend' => $avgSpend,
            'gap' => $gap,
            'basis' => $basis,
            'spentNow' => $spentNow,
            'left' => $left,
            'dailyLeft' => $dailyLeft,
            'emergency' => $emergency,
            'tips' => $tips,
        ];
    }
}
