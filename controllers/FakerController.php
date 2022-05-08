<?php

namespace app\controllers;

use app\models\Supplier;
use yii\db\Exception;
use yii\web\Controller;

class FakerController extends Controller
{
    public function actionIndex()
    {
        $faker = \Faker\Factory::create('zh_CN');

        $rows = [];
        for($i=0; $i<50; $i++) {
            $rows[] = [
                'name' => $faker->unique()->company(),
                'code' => $faker->unique()->currencyCode(),
                't_status' => $faker->randomElement(['ok', 'hold']),
            ];
        }

        $model = new Supplier();
        try {
            \Yii::$app->db->createCommand()->batchInsert(Supplier::tableName(), ['name', 'code', 't_status'], $rows)->execute();
        } catch (Exception $e) {
        }
    }
}