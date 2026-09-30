<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expense".
 *
 * @property int $id
 * @property string $title
 * @property float $amount
 * @property string $category
 * @property string $spent_on
 */
class Expense extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'amount', 'category', 'spent_on'], 'required'],
            ['amount', 'number', 'min' => 0.01, 'tooSmall' => 'The amount must be more than RM 0.'],
            ['spent_on', 'date', 'format' => 'php:Y-m-d'],
            [['title'], 'string', 'max' => 120],
            [['category'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'amount' => 'Amount',
            'category' => 'Category',
            'spent_on' => 'Spent On',
        ];
    }
}
