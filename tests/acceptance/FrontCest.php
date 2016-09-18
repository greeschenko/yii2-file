<?php

class FrontCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryTestPageWork(AcceptanceTester $I)
    {
        $I->wantTo('check test page');
        $I->amOnPage('/file/test');
        $I->see('File Module Test Page');
    }

    public function tryUploadImgs(AcceptanceTester $I)
    {
        /*все акшины в файл контроллер*/
        /*аплоад модель с возможностью задать параметри постобработки
            массив параметров*/
        //общая таблица связи изображения и модели элемента
            //или по ид + тип элемента
        //файли на мете после перезагрузки страницы
        $I->wantTo('check img upload');
        $I->amOnPage('/file/test');

        $I->click('#fileuploadfield');
        $I->click('#fileuploadfield');
        $I->attachFile('#fileuploadfield',  'test.jpg');
        $I->attachFile('#fileuploadfield',  'test1.jpg');
        if (method_exists($I, 'wait')) {
            $I->wait(5);
        }
        $I->see('test.jpg');
        $I->see('test1.jpg');
    }

    public function tryEditImgsMeta(AcceptanceTester $I)
    {
        $I->wantTo('try to edit image title and description');
        $I->amOnPage('/file/test');
        $I->see('#fileitem_0');
        $I->see('#fileitem_1');

        $I->fillField("#fileitem_0_title",  "test title 1");
        $I->fillField("#fileitem_1_title",  "test title 2");
        $I->fillField("#fileitem_0_description",  "test description 1");
        $I->fillField("#fileitem_1_description",  "test description 2");
        $I->click('#fileitem_0_save');
        $I->see('test title 1 info saved!');
        $I->click('#fileitem_1_save');
        $I->see('test title 2 info saved!');
    }

    public function tryViewImgs(AcceptanceTester $I)
    {
        $I->wantTo('try to view img');
        $I->amOnPage('/file/test');
        $I->see('#fileitem_0');
        $I->see('#fileitem_1');
        $I->click('#fileitem_0_view');
        $I->see('#fileviewer_next');
        $I->click('#fileviewer_close');
        $I->dontSee('#fileviewer_next');
    }

    public function tryEditImgs(AcceptanceTester $I)
    {
        /*редактировать
            окно со всеми вариантами
            возможность рекропа каждого варианта*/

        $I->wantTo('try to edit img');
        $I->amOnPage('/file/test');
        $I->click('#fileitem_0_edit');
        $I->see('#file_edit_modal');
        $I->see('#file_reupload');
        $I->see('#file_size_item_0');
        $I->see('#file_size_item_1');
        $I->see('#file_size_item_2');

        $I->click('#file_reupload');
        //TODO использовать https://blueimp.github.io/jQuery-File-Upload/basic.html
        //простой attachFile в нужный инпут должен запустить скрипт

        $I->click('#file_size_item_0_recrop');
        $I->see('#file_recrop_modal');
        //TODO попробовать эмулировать драгндроп через $I->executeJS(...)
        // $('#element').simulate('drag', { dx: 50, dy: 50 });
    }

    public function tryChangeImgPosition(AcceptanceTester $I)
    {
        //TODO попробовать эмулировать драгндроп через $I->executeJS(...)
        // $('#element').simulate('drag', { dx: 50, dy: 50 });
        $I->wantTo('try to change img position');
        $I->amOnPage('/file/test');
    }

    public function trySetImgMain(AcceptanceTester $I)
    {
        $I->wantTo('try set img main');
        $I->amOnPage('/file/test');
        $I->click('#fileitem_0_setmain');
        $I->reloadPage();
        $I->see('#fileitem_0.main');
    }

    public function tryUnSetImgMain(AcceptanceTester $I)
    {
        $I->wantTo('try set img not main');
        $I->amOnPage('/file/test');
        $I->click('#fileitem_0_setmain');
        $I->reloadPage();
        $I->dontSee('#fileitem_0.main');
    }

    public function tryDeleteImg(AcceptanceTester $I)
    {
        $I->wantTo('try set img not main');
        $I->amOnPage('/file/test');
        $I->click('#fileitem_0_delete');
        $I->see('File delete successful!');
    }

    public function tryUploadCastomFile(AcceptanceTester $I)
    {
    }

    public function tryAddDirrectLinkFile(AcceptanceTester $I)
    {
    }
}
