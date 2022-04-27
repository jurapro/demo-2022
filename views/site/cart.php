<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;


$this->registerJsFile(
    '@web/js/main.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);


?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'cart']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'product.name',
            'count',
            [
                'label' => 'Добавить в корзину',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button onclick='addCart($data->product_id)' class='btn btn-success'> + </button>";
                },

            ],

            [
                'label' => 'Удалить',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button onclick='removeCart($data->product_id)' class='btn btn-success'> - </button>";
                },

            ],
        ],

    ]); ?>
    <?php Pjax::end() ?>
    <div class="form-group">
    <?= Html::input('password', 'password', '', ['class' => 'form-control password']) ?>
    </div>
    <?= Html::button('Оформить заказ', [
            'class' => 'btn btn-success',
            'onclick'=>'byOrder()'
    ]) ?>

</div>
