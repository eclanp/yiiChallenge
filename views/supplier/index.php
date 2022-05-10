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
        <a style="display: none;" href="#" id="export-btn" class="btn btn-info">Export CSV</a>
    </p>

    <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>

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

    <p style="display: none;" id="all_page_selector_tip">All <span id="result_count">0</span> conversations on this page have been selected. <a href="#" id="all_page_selector">Select all conversations that match this search</a></p>
    <p style="display: none;" id="all_page_selected_tip">All conversations in this search have been selected. <a href="#" id="all_page_unselect">Clear selection</a></p>
</div>

<?php
$js = <<<JS
$(function () {
    var resultCount = ($('#w0 > div.summary').children('b:last-child')).text();
    $('#result_count').text(resultCount);
    
    if (localStorage.getItem('selected') === 'Y') { //全选所有页
        $('input.select-on-check-all').click();
        showTips();
        showExportBtn();
    }
    
    $('input[type="checkbox"]').on('change', function () {
        showExportBtn();
    });
    
    $('input.select-on-check-all').on('change', function (e) {
        var isCheckedAll = $(this).prop('checked');
        if (isCheckedAll) { //当前页全选
            showTips();
        } else { //当前页未全选
            unSelectAllPage(true);
        }
    });
    
    $('#all_page_selector').on('click', function () {
        selectAllPage();
    });
    
    $('#all_page_unselect').on('click', function () {
        unSelectAllPage();
    });
    
    $('#export-btn').on('click', function () {
        doExport();
    });
});

function showTips() {
    if (localStorage.getItem('selected') === 'Y') { //全选所有页
        $('#all_page_selected_tip').show();
        $('#all_page_selector_tip').hide();
    } else {
        $('#all_page_selector_tip').show();
        $('#all_page_selected_tip').hide();
    }
}

function showExportBtn() {
    var ids = $('#w0').yiiGridView('getSelectedRows');
    // console.log('ids: ', ids);
    if (ids.length > 0) {
        $('#export-btn').show();
    } else {
        $('#export-btn').hide();
    }
}

function doExport() {
    var exportUrl = '/supplier/export';
    var params = [];
    $('[name^="SupplierSearch"]').each(function (k, v) {
        var val = $(v).val();
        if (val !== "") {
            params[params.length] = $(v).attr('name') + '=' + val;
        }
    });
    
    if (params.length > 0) {
        exportUrl += '?' + params.join('&')
    }
    
    if (localStorage.getItem('selected') !== 'Y') {
        var ids = $('#w0').yiiGridView('getSelectedRows');
        if (ids.length < 1) {
            alert('请至少选择一条要导出的项');
            return false;
        }
        
        if (params.length > 0) {
            exportUrl += '&ids=' + ids.join(',')
        } else {
            exportUrl += '?ids=' + ids.join(',')
        }
    }
    
    window.location.href = exportUrl;
}

function selectAllPage() {
    localStorage.setItem('selected', 'Y');
    showTips();
}

function unSelectAllPage(hide = false) {
    localStorage.removeItem('selected');
    if (hide) {
        $('#all_page_selected_tip').hide();
        $('#all_page_selector_tip').hide();
    } else {
        $('#all_page_selector_tip').show();
        $('#all_page_selected_tip').hide();
    }
}
JS;

$this->registerJs($js);

