<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $date
 * @property string $name
 * @property string|null $file
 * @property int|null $count
 * @property int|null $year
 * @property string|null $model
 * @property string|null $country
 * @property int|null $category_id
 *
 * @property Cart[] $carts
 * @property Category $category
 * @property ProductOrder[] $productOrders
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'price'], 'safe'],
            [['name'], 'required'],
            [['count', 'year', 'category_id'], 'integer'],
            [['name', 'file', 'model', 'country'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $fileName = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($fileName);
            $this->file = '/' . $fileName;
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'name' => 'Name',
            'file' => 'File',
            'count' => 'Count',
            'year' => 'Year',
            'model' => 'Model',
            'country' => 'Country',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[ProductOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['product_id' => 'id']);
    }
}
