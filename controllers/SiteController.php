<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\Product;
use app\models\ProductOrder;
use app\models\Status;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'cart', 'to-cart', 'remove-cart', 'by-order'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->where(['>', 'count', 0]),
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCart()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cart::find()->where(['user_id' => \Yii::$app->user->id]),
        ]);

        return $this->render('cart', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionToCart($id_product)
    {
        $product = Product::find()
            ->where(['id' => $id_product])
            ->andWhere(['>', 'count', 0])
            ->one();

        if (!$product) {
            return "Такого продукта нет";
        }

        $itemInCart = Cart::find()
            ->where(['product_id' => $id_product])
            ->andWhere(['user_id' => Yii::$app->user->getId()])
            ->one();

        if (!$itemInCart) {
            $itemInCart = new Cart([
                'product_id' => $id_product,
                'user_id' => Yii::$app->user->getId(),
                'count' => 1
            ]);
            $itemInCart->save();
            return "Продукт добавлен. Количество товаров в корзине = $itemInCart->count";
        }

        if ($itemInCart->count + 1 > $product->count) {
            return "Нельзя больше добавить";
        }

        $itemInCart->count++;
        $itemInCart->save();
        return "Продукт добавлен. Количество товаров в корзине = $itemInCart->count";
    }

    public function actionRemoveCart($id_product)
    {
        $itemInCart = Cart::find()
            ->where(['product_id' => $id_product])
            ->andWhere(['user_id' => Yii::$app->user->getId()])
            ->one();

        if (!$itemInCart) {
            return;
        }

        if ($itemInCart->count - 1 == 0) {
            $itemInCart->delete();
            return;
        }

        $itemInCart->count--;
        $itemInCart->save();
        return;
    }

    public function actionByOrder($password)
    {
        if (!Yii::$app->user->identity->validatePassword($password)) {
            return "Пароль не верный";
        }

        $order = new Order([
            'user_id' => Yii::$app->user->getId(),
            'status_id' => Status::find()->where(['code' => 'new'])->one()->id
        ]);
        $order->save();

        $itemInCart = Yii::$app->user->identity->carts;

        foreach ($itemInCart as $item) {
            $itemInOrder = new ProductOrder([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'count' => $item->count,
                'price' => $item->product->price * $item->count,
            ]);
            $itemInOrder->save();
            $item->delete();
        }
        return "Заказ сформирован";
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
