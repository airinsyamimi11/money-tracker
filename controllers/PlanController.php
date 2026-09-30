<?php

namespace app\controllers;

use app\models\Budget;
use app\models\Planner;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class PlanController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['apply' => ['POST']],
            ],
        ];
    }

    public function actionIndex(int $save = 20)
    {
        return $this->render('index', ['p' => Planner::build($save)]);
    }

    /** Copies the plan's monthly amount into this month's budget. */
    public function actionApply(int $save = 20)
    {
        $plan = Planner::build($save);

        if ($plan['income'] <= 0) {
            Yii::$app->session->setFlash('error', 'Add your income first.');

            return $this->redirect(['index']);
        }

        $month = date('Y-m');
        $budget = Budget::findOne(['month' => $month]) ?? new Budget(['month' => $month]);
        $budget->amount = round($plan['monthly'], 2);

        if ($budget->save()) {
            Yii::$app->session->setFlash('success', 'Your budget for ' . date('F Y') . ' is now set from your plan.');

            return $this->redirect(['/site/index']);
        }

        Yii::$app->session->setFlash('error', 'Could not save the budget.');

        return $this->redirect(['index']);
    }
}
