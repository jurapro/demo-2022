<?php
/* @var $this yii\web\View */

use app\models\Status;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

?>
<h1>admin/index</h1>

<p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            [
                'label' => 'Статус',
                'attribute' => 'status_id',
                //'filter' => ['1' => 'Новый', '2' => 'Подтвержденный', '3' => 'Отмененный'],
                'filter' => ArrayHelper::map(Status::find()->asArray()->all(),'id','name'),
                'value' => 'status.name'
            ],

            [
                'label' => 'Количество товаров',
                'value' => function ($data) {
                    return count($data->productOrders);
                }
            ],
            [
                'label' => 'ФИО заказчика',
                'value' => function ($data) {
                    return $data->user->name . ' ' . $data->user->surname . ' ' . $data->user->patronymic;
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
