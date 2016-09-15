<?php
use greeschenko\file\models\UploadForm;
use dosamigos\fileupload\FileUpload;
?>

<h1>File Module Test Page</h1>
<div class="hidden">
    <?= FileUpload::widget([
        'model' => new UploadForm,
        'attribute' => 'filedata',
        'url' => ['site/ajax-upload'],
        'options' => [
            /*'accept' => 'image/*',*/
            'multiple' => true,
        ],
        'clientOptions' => [
            'maxFileSize' => 10000000,
            'dataType' => 'json',
        ],
        // Also, you can specify jQuery-File-Upload events
        // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
        'clientEvents' => [
            'fileuploaddone' => 'function (e, data) {
                var id = data.result.files[0].id;
                $.ajax({
                    method: "GET",
                    url: "/files/changegroup",
                    data: "id="+id+"&gcode="+currentgcode,
                    dataType: "text",
                    success:function(data, textStatus, xhr) {
                        $.ajax({
                            method: "GET",
                            url: "/files/get-group-list",
                            data: "gcode="+currentgcode,
                            dataType: "html",
                            success:function(data, textStatus, xhr) {
                                $("#res_"+currentgcode).html(data);
                                deleteImgInit();
                            },
                        });
                    },
                });
                console.log(e);
                console.log(data.result);
            }',
            'fileuploadfail' => 'function (e, data) {
                console.log(e);
                console.log(data);
            }',
            'fileuploadstart' => 'function (e) {
                $("#res_"+currentgcode).html(\'<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>\');
            }',
        ],
    ]);?>
</div>
