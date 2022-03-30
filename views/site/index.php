<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            'name',
            [
                'label' => 'Изображение',
                'format'=>'html',
                'value' => function ($data) {
                    return Html::img($data->file, ['width' => 200]);
                }
            ],
            'count',
        ],
    ]); ?>
</div>
