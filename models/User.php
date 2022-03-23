<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Expression;
use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password_repeat;
    public $rules;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'username', 'email', 'password', 'rules'], 'required'],
            [['name', 'surname', 'patronymic'], 'match', 'pattern' => '/^[а-яА-Я -]*$/u',
                'message' => 'Разрешены только кириллица, пробел или тире'],

            [['role_id'], 'integer'],
            [['name', 'surname', 'patronymic', 'username', 'email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9-]*$/i',
                'message' => 'Разрешены только латиница, цифры или тире'],
            [['email'], 'unique'],
            [['email'], 'email'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            [['password'], 'string', 'min' => 6],
            ['rules', 'compare', 'compareValue' => 1, 'message' => 'Необходимо принять условия регистрации'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Роль',
            'password_repeat' => 'Повтор пароля',
            'rules' => 'Согласие с правилами регистрации',
        ];
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function beforeSave($insert)
    {
        $this->password = md5($this->password);
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function isAdmin()
    {
        return $this->role->code === 'admin';
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }
}
