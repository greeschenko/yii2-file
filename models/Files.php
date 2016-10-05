<?php

namespace greeschenko\file\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $ext
 * @property string $preset
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $type
 */
class Files extends \yii\db\ActiveRecord
{
    const TYPE_IMG = 1;
    const TYPE_DOC = 2;
    const TYPE_LINK = 3;

    public $module;
    public $iconlist = [
        'doc' => '<i class="fa fa-file-word-o" aria-hidden="true"></i>',
        'docx' => '<i class="fa fa-file-word-o" aria-hidden="true"></i>',
        'xls' => '<i class="fa fa-file-excel-o" aria-hidden="true"></i>',
        'xlsx' => '<i class="fa fa-file-excel-o" aria-hidden="true"></i>',
        'pdf' => '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
        'zip' => '<i class="fa fa-file-archive-o" aria-hidden="true"></i>',
        'rar' => '<i class="fa fa-file-archive-o" aria-hidden="true"></i>',
    ];
    public $linkiconlist = [
        'dirrect' => '<i class="fa fa-external-link" aria-hidden="true"></i>',
    ];

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
    }

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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->identity->id;

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','path','ext','preset'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'status', 'type'], 'integer'],
            [['name', 'path','preset'], 'string', 'max' => 255],
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
            'preset' => Yii::t('app', 'Preset'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
        ];
    }

    public function getData()
    {
        $res = [];

        if ($this->type == self::TYPE_IMG) {
            $sizes = $this->module->presets[$this->preset]['sizes'];
            foreach ($sizes as $i=>$one) {
                $res[$i] = $this->path.$this->name.'_'.$i.'_'.'.'.$this->ext;
            }
        } elseif ($this->type == self::TYPE_LINK) {
            $res['url'] = $this->path;
            $res['icon'] = $this->linkiconlist['dirrect'];
        } else {
            $res['url'] = $this->path.$this->name.'.'.$this->ext;
            $res['icon'] = $this->iconlist[$this->ext];
        }

        return array_merge($this->attributes,$res);
    }

    //TODO make all img remove $model->clearAll();
    /*foreach ($model->getFormatList() as $one) {
        @unlink($model->path.$model->name.$one.$model->ext);
    }*/
}
