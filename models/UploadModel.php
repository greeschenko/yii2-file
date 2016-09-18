<?php
namespace greeschenko\file\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\base\NotSupportedException;

class UploadModel extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $filedata;
    public $path = 'uploads/';
    public $urlpath = '';
    public $extensions = 'png, jpg, jpeg, gif, tiff, doc, docx, xls, xlsx, pdf, zip, rar, PNG, JPG, JPEG, GIF, TIFF, DOC, DOCX, XLS, XLSX, PDF, ZIP, RAR';
    public $maxFiles = 1000;
    public $maxImgW = 1920;
    public $maxImgH = 1080;
    public $mid = 1400;
    public $small = 840;
    public $imgQ = 90;
    public $tumbQ = 70;
    public $maxTumbHW = 280;
    public $minTumbHW = 180;

    public function rules()
    {
        return [
            [
                ['filedata'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => $this->extensions,
                'maxFiles' => $this->maxFiles
            ],
        ];
    }

    public function upload($model=false)
    {
        $res = [];
        $timedir = strtotime(date('d-m-Y',time())).'/';

        if ( !is_dir($this->path) ) {
            mkdir($this->path);
        }

        if ( !is_dir($this->path.$timedir) ) {
            mkdir($this->path.$timedir);
        }

        $this->path = $this->path.$timedir;

        if ($this->validate()) {
            $n = 0;
            foreach ($this->filedata as $file) {
                $ext = strtolower($file->extension);
                $filename = trim($file->baseName);
                $filename = $this->translite($filename);
                $filename = strtolower($filename);
                $src = $this->path . $filename . '.' . $ext;

                $file->saveAs($src);
                //image lib info https://github.com/yurkinx/yii2-image

                if ($ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'png'
                     or $ext == 'JPG' or $ext == 'JPEG' or $ext == 'GIF' or $ext == 'PNG'
                ) {
                    $img = getimagesize($src);
                    $w = $img[0];
                    $h = $img[1];

                    if ($h > $this->maxImgH and $w > $this->maxImgW) {
                        Yii::$app->image
                            ->load($src)
                            ->resize($this->maxImgW, $this->maxImgH, \yii\image\drivers\Image::AUTO)
                            ->save($this->path.$filename.'_r.jpg',$this->imgQ);
                    } else {
                        Yii::$app->image
                            ->load($src)
                            ->save($this->path.$filename.'_r.jpg',$this->imgQ);
                    }

                    Yii::$app->image
                        ->load($src)
                        ->resize($this->mid, $this->mid, \yii\image\drivers\Image::AUTO)
                        ->save($this->path.$filename.'_m.jpg',$this->tumbQ);

                    Yii::$app->image
                        ->load($src)
                        ->resize($this->small, $this->small, \yii\image\drivers\Image::AUTO)
                        ->save($this->path.$filename.'_s.jpg',$this->tumbQ);

                    Yii::$app->image
                        ->load($src)
                        ->resize($this->maxTumbHW, $this->maxTumbHW, \yii\image\drivers\Image::ADAPT)
                        ->background('#000')
                        ->save($this->path.$filename.'_a.jpg',$this->tumbQ);

                    Yii::$app->image
                        ->load($src)
                        ->resize($this->maxTumbHW, $this->maxTumbHW, \yii\image\drivers\Image::CROP)
                        ->save($this->path.$filename.'_c.jpg',$this->tumbQ);

                    Yii::$app->image
                        ->load($src)
                        ->resize($this->minTumbHW, $this->minTumbHW, \yii\image\drivers\Image::AUTO)
                        ->save($this->path.$filename.'_t.jpg',$this->tumbQ);

                    if (!$model) {
                        $model = new Files();
                        $model->name = $filename;
                        $model->path = $timedir;
                        $model->ext = 'jpg';
                        $model->type = Files::TYPE_IMG;
                        if ( !$model->save() ) {
                            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
                        }
                    } else {
                        foreach ($model->getFormatList() as $one) {
                            @unlink($model->path.$model->name.$one.$model->ext);
                        }
                        $model->name = $filename;
                        $model->path = $timedir;
                        $model->ext = 'jpg';
                        $model->type = Files::TYPE_IMG;
                        if ( !$model->save() ) {
                            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
                        }
                    }

                    $res[] = [
                        'id'=>$model->id,
                        'name'=>$filename,
                        'url'=>'/'.$this->path.$filename.'_r.jpg',
                        'thumbnailUrl'=>'/'.$this->path.$filename.'_t.jpg',
                        'size'=>$file->size,
                        'deleteUrl'=>'/files/delete/?id='.$model->id,
                        'deleteType'=>'POST',
                        'is_image'=>1,
                    ];

                    $n++;
                    @unlink($src);
                } else {
                    if (!$model) {
                        $model = new Files();
                        $model->name = $filename;
                        $model->path = $timedir;
                        $model->ext = $ext;
                        $model->type = Files::TYPE_DOC;
                        if ( !$model->save() ) {
                            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
                        }
                    } else {
                        foreach ($model->getFormatList() as $one) {
                            @unlink($model->path.$model->name.$one.$model->ext);
                        }
                        $model->name = $filename;
                        $model->path = $timedir;
                        $model->ext = $ext;
                        $model->type = Files::TYPE_DOC;
                        if ( !$model->save() ) {
                            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
                        }
                    }

                    $res[] = [
                        'id'=>$model->id,
                        'name'=>$filename,
                        'url'=>'/'.$this->path.$filename.'.'.$ext,
                        /*TODO можно сделать вывод иконок разных'thumbnailUrl'=>'/'.$this->path.$filename.'_t.jpg',*/
                        'size'=>$file->size,
                        'deleteUrl'=>'/files/delete/?id='.$model->id,
                        'deleteType'=>'POST',
                        'is_image'=>0,
                    ];
                }
            }
            $files = ['files'=>$res];

            return json_encode($files);
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
