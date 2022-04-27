<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';
?>

<div class="site-index">

    <?php Pjax::begin(['id' => 'products']) ?>
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

            [
                'label' => 'Добавить в корзину',
                'format'=>'raw',
                'value' => function ($data) {
                    return Html::button('В корзину',
                        [
                                'class' => 'btn btn-primary',
                                'onclick' => "(function () { 
                                $.ajax({
                                method: 'POST',
                                url: '/site/to-cart?id_product=$data->id'
                                }).done(function(msg){
                                $.pjax.reload({container:'#products'});  
                                alert(msg); 
                                })                        
                                })();"
                        ]);
                }
            ],
            'count',
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
