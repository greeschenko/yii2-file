yii2constructive file module
============================
Highly variable file and img upload and attachment plagin

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

add

```
    "require": {
        ...
        "greeschenko/yii2-file": "*"
        ...
    },
```

to the `composer.json` file.


update database

$ php yii migrate/up --migrationPath=@vendor/greeschenko/yii2-file/migrations


Usage
-----

add to you app config

```
'modules'=>[
    'file'=> [
        'class'=>'greeschenko\file\Module',
    ],
],

```
