<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';

$this->registerJsFile(
    '@web/js/main.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);


?>
<div class="site-index">
    <?php Pjax::begin(['id' => 'cart']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            'name',
            [
                'label' => 'Изображение',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data->file, ['width' => 200]);
                }
            ],
            [
                'label' => 'Добавить в корзину',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button onclick='toCart($data->id)' class='btn btn-success'>Добавить в корзину</button>";
                },
                'visible' => Yii::$app->user->identity
            ],
            'count',
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
