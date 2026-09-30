<?php
namespace app\models;

class Summary
{
    public static function forMonth(string $month): array
    {
        $start = "$month-01";
        $end = date('Y-m-t', strtotime($start));
        $daysInMonth = (int) date('t', strtotime($start));
        $isCurrent = $month === date('Y-m');
        $day = $isCurrent ? (int) date('j') : $daysInMonth;
        $daysLeft = $isCurrent ? $daysInMonth - $day + 1 : 1; // includes today

        $budget = (float) Budget::find()->select('amount')->where(['month' => $month])->scalar();
        $expenses = Expense::find()->where(['between', 'spent_on', $start, $end]);
        $spent = (float) $expenses->sum('amount');

        $remaining = $budget - $spent;
        $daily = $remaining > 0 ? $remaining / $daysLeft : 0;
        $projected = $day > 0 ? $spent / $day * $daysInMonth : 0;

        $byCategory = Expense::find()
            ->select(['category', 'total' => 'SUM(amount)'])
            ->where(['between', 'spent_on', $start, $end])
            ->groupBy('category')->orderBy(['total' => SORT_DESC])
            ->asArray()->all();

        return [
            'month' => $month,
            'budget' => $budget,
            'spent' => $spent,
            'remaining' => $remaining,
            'daily' => $daily,
            'weekly' => $daily * 7,
            'projected' => $projected,
            'byCategory' => $byCategory,
            'tips' => self::tips($budget, $spent, $projected, $byCategory),
        ];
    }

    private static function tips(float $budget, float $spent, float $projected, array $cats): array
    {
        $tips = [];
        if ($budget <= 0) {
            return ['Set a monthly budget first. A good starting point is the 50/30/20 rule: 50% needs, 30% wants, 20% savings.'];
        }
        if ($projected > $budget) {
            $tips[] = 'At your current pace you will overspend by RM ' . number_format($projected - $budget, 2) . ' this month. Try cutting non-essentials for a few days.';
        }
        if ($spent > $budget) {
            $tips[] = 'You have already exceeded your budget. Pause all discretionary spending for the rest of the month.';
        }
        if ($cats && $spent > 0 && $cats[0]['total'] / $spent > 0.35) {
            $tips[] = '"' . $cats[0]['category'] . '" takes ' . round($cats[0]['total'] / $spent * 100) . '% of your spending. This is the best place to look for savings.';
        }
        $tips[] = 'Pay yourself first: move your savings out on payday rather than saving what is left over.';
        $tips[] = 'Wait 24 hours before any non-essential purchase over RM 100.';
        $tips[] = 'Cook at home a few more days a week; food delivery is often the biggest hidden cost.';
        return $tips;
    }
}