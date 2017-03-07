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
            [['group', 'title','bind'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('file', 'ID'),
            'group' => Yii::t('file', 'Group'),
            'file_id' => Yii::t('file', 'File ID'),
            'title' => Yii::t('file', 'Title'),
            'description' => Yii::t('file', 'Description'),
            'is_main' => Yii::t('file', 'Is Main'),
        ];
    }

    public function getFile()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    /**
     * return all attachment in select group
     *
     * @return void
     */
    public static function getGroupData($gcode)
    {
        $data = static::find()
            ->where(['group' => $gcode])
            ->all();

        return $data;
    }

    public static function getCountByCode($gcode)
    {
        $data = static::find()
            ->where(['group' => $gcode])
            ->count();

        return $data;
    }
}
