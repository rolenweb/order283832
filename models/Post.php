<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $id_router
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $urlimg
 * @property string $text
 * @property integer $status
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_router', 'created_at', 'updated_at', 'status'], 'integer'],
            
            [['text'], 'string'],
            [['urlimg'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_router' => 'Id Router',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'urlimg' => 'Urlimg',
            'text' => 'Text',
            'status' => 'Status',
        ];
    }
}
