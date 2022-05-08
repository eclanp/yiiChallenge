<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Supplier', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            'name',
            'code',
            [
                'attribute' => 't_status',
                'value' => function ($model) {
                    return strtoupper( $model->t_status );
                },
                'filter' => [
                    'ok' => 'OK',
                    'hold' => 'HOLD',
                ],
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \app\models\Supplier $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <>
</div>

<?php
$js = <<<JS
$(function () {
    $('input.select-on-check-all').on('change', function (e) {
        var isCheckedAll = $(this).prop('checked');
        if (isCheckedAll) {
            
        } else {
            
        }
    })
});
JS;

$this->registerJs($js);

