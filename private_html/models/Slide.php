<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 * @property $image string
 * @property $link string
 *
 */
class Slide extends Item
{
    public static $modelName = 'slide';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    public function init()
    {
        parent::init();
        $this->dynaDefaults = array_merge($this->dynaDefaults, [
            'link' => ['CHAR', ''],
            'image' => ['CHAR', ''],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['image'], 'required'],
            [['link'], 'string'],
            ['modelID', 'default', 'value' => Model::findOne(['name' => self::$modelName])->id],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'link' => Yii::t('words', 'Link'),
            'image' => Yii::t('words', 'Image'),
        ]);
    }

    /**
     * {@inheritdoc}
     * @return ItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemQuery(get_called_class());
    }
}
