<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\Product;
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
                        'actions' => ['logout', 'cart', 'to-cart'],
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
            ->andwhere(['>', 'count', 0])
            ->one();

        if (!$product) {
            return 'Такого продукта нет';
        }

        $itemInCart = Cart::find()
            ->where(['product_id' => $id_product])
            ->andwhere(['user_id' => Yii::$app->user->id])
            ->one();

        if (!$itemInCart) {
            $itemInCart = new Cart([
                'product_id' => $id_product,
                'user_id' => Yii::$app->user->id,
                'count' => 1
            ]);
            $itemInCart->save();
            return 'Продукт добавлен. Количество товаров в корзине = ' . $itemInCart->count;
        }

        if ($itemInCart->count + 1 > $product->count) {
            return 'Нельзя добавить больше';
        }
        $itemInCart->count++;
        $itemInCart->save();
        return 'Продукт добавлен. Количество товаров в корзине = ' . $itemInCart->count;
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
