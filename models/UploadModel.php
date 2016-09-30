<?php
namespace greeschenko\file\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadModel extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $module;
    public $preset;
    public $filedata;
    public $path = 'uploads';
    public $urlpath = '';

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
    }

    public function rules()
    {
        return [
            array_merge(
                [ 'filedata', 'file', 'skipOnEmpty' => false, ],
                $this->module->presets[$this->preset]['rules']
            )
        ];
    }

    public function upload($model=false)
    {
        $res = [];
        $timedir = '/'.strtotime(date('d-m-Y',time())).'/';

        if ( !is_dir($this->path) ) {
            mkdir($this->path);
        }

        if ( !is_dir($this->path.$timedir) ) {
            mkdir($this->path.$timedir);
        }

        $this->path = $this->path.$timedir;

        if ($this->validate()) {
            foreach ($this->filedata as $n=>$file) {
                $ext = strtolower($file->extension);
                $filename = trim($file->baseName);
                $filename = $this->translite($filename);
                $filename = strtolower($filename);
                $src = $this->path . $filename . '.' . $ext;

                $file->saveAs($src);
                //image lib info https://github.com/yurkinx/yii2-image

                if ( in_array($ext,[ 'png', 'jpg', 'jpeg', 'gif',
                    'tiff', 'PNG', 'JPG', 'JPEG', 'GIF', 'TIFF', ])
                ) {
                    $img = getimagesize($src);
                    $w = $img[0];
                    $h = $img[1];
                    $sizes = $this->module->presets[$this->preset]['sizes'];

                    foreach ($sizes as $i=>$one) {
                        $this->module->image
                            ->load($src)
                            ->resize($one['width'], $one['height'],
                                constant('\yii\image\drivers\Image::'.$one['mode']))
                            ->save($this->path.$filename.'_'.$i.'_'.'.'.$ext,$one['quality']);
                    }
                    $type = Files::TYPE_IMG;
                    @unlink($src);
                } else {
                    $type = Files::TYPE_DOC;
                }

                if (!$model) {
                    $model = new Files();
                    $model->name = $filename;
                    $model->path = '/'.$this->path;
                    $model->ext = $ext;
                    $model->size = $file->size;
                    $model->preset = $this->preset;
                    $model->type = $type;
                    if ( !$model->save() ) {
                        throw new \yii\web\HttpException(
                            501 ,
                            json_encode($model->errors)
                        );
                    }
                } else {
                    $model->clearAll();
                    $model->name = $filename;
                    $model->path = '/'.$this->path;
                    $model->ext = $ext;
                    $model->size = $file->size;
                    $model->type = $type;
                    if ( !$model->save() ) {
                        throw new \yii\web\HttpException(
                            501 ,
                            json_encode($model->errors)
                        );
                    }
                }

                $res = $model->getData();
            }

            $files = ['files'=>$res];

            return $files;
        } else {
            return false;
        }
    }

    public function translite($str)
    {
        $cyr  = array('а','б','в','г','д','e','ё','ж','з','и','й',
            'к','л','м','н','о','п','р','с','т','у',
            'ф','х','ц','ч','ш','щ','ъ', 'ы','ь', 'э',
            'ю','я','А','Б','В','Г','Д','Е','Ж','З','И',
            'Й','К','Л','М','Н','О','П','Р','С','Т','У',
            'Ф','Х','Ц','Ч','Ш','Щ','Ъ', 'Ы','Ь', 'Э', 'Ю','Я' );

        $lat = array( 'a','b','v','g','d','e','io','zh','z','i',
        'y','k','l','m','n','o','p','r','s','t','u',
        'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a', 'i', 'y',
        'e' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
        'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
        'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );

        $res = str_replace($cyr, $lat, $str);
        $res = str_replace(" ", "_", $res);

        return $res;
    }
}
