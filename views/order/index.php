<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            'status.name',
            [
                'label' => 'Количество товаров',
                'value' => function ($data) {
                    return count($data->productOrders);
                }
            ],
            [
                'label' => 'Список товаров',
                'format' => 'html',
                'value' => function ($data) {
                    $res = [];
                    foreach ($data->productOrders as $item) {
                        $res[] = $item->product->name;
                    }
                    return join('<br>', $res);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->status->code === 'new';
                    },
                ]
            ],
        ],
    ]); ?>


</div>
