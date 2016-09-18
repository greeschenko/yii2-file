<?php

namespace greeschenko\file\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $ext
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $type
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'path', 'ext', 'user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'status', 'type'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
            [['ext'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'path' => Yii::t('app', 'Path'),
            'ext' => Yii::t('app', 'Ext'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
        ];
    }
}
