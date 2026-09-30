<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property float $amount
 * @property string $frequency
 */
class Income extends ActiveRecord
{
    public const FREQ = [
        'monthly' => 'Every month',
        'weekly' => 'Every week',
    ];

    public static function tableName()
    {
        return 'income';
    }

    public function rules()
    {
        return [
            [['title', 'amount', 'frequency'], 'required'],
            ['title', 'string', 'max' => 80],
            ['amount', 'number', 'min' => 0.01, 'tooSmall' => 'The amount must be more than RM 0.'],
            ['frequency', 'in', 'range' => array_keys(self::FREQ)],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Where does it come from?',
            'amount' => 'Amount (RM)',
            'frequency' => 'How often?',
        ];
    }

    /** This income converted to a monthly amount. */
    public function monthlyAmount(): float
    {
        return $this->frequency === 'weekly'
            ? (float) $this->amount * 52 / 12
            : (float) $this->amount;
    }

    /** All income sources added together, per month. */
    public static function monthlyTotal(): float
    {
        $total = 0.0;
        foreach (self::find()->all() as $income) {
            $total += $income->monthlyAmount();
        }

        return $total;
    }
}
