<?php

namespace greeschenko\file\models;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property integer $id
 * @property string $group
 * @property integer $file_id
 * @property string $title
 * @property string $description
 * @property integer $is_main
 */
class Attachments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'file_id'], 'required'],
            [['file_id', 'is_main'], 'integer'],
            [['description'], 'string'],
            [['group', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'group' => Yii::t('app', 'Group'),
            'file_id' => Yii::t('app', 'File ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'is_main' => Yii::t('app', 'Is Main'),
        ];
    }
}
