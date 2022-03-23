<?php

use app\models\Status;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

?>
<h1>Административная панель</h1>

<p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            [
                'label' => 'Статус заказа',
                'attribute' => 'status_id',
                'filter' => ArrayHelper::map(Status::find()->asArray()->all(), 'id', 'name'),
                'value' => 'status.name'
            ],
            [
                'label' => 'ФИО клиента',
                'value' => function ($data) {
                    return "{$data->user->name} {$data->user->surname} {$data->user->patronymic}";
                }
            ],
            [
                'label' => 'Количество товаров',
                'value' => function ($data) {
                    return count($data->productOrders);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->status->code === 'new';
                    },
                ]
            ],
        ],
    ]); ?>
</p>
