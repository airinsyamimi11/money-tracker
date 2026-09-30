<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "budget".
 *
 * @property int $id
 * @property string $month
 * @property float $amount
 */
class Budget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'budget';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['month', 'amount'], 'required'],
            ['amount', 'number', 'min' => 0.01, 'tooSmall' => 'The budget must be more than RM 0.'],
            ['month', 'match',
                'pattern' => '/^\d{4}-(0[1-9]|1[0-2])$/',
                'message' => 'Please pick a valid month.',
            ],
            ['month', 'unique', 'message' => 'You already set a budget for this month. Edit that one instead.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'month' => 'Month',
            'amount' => 'Budget (RM)',
        ];
    }
}