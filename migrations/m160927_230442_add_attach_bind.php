<?php

use yii\db\Migration;

class m160927_230442_add_attach_bind extends Migration
{
    public function up()
    {
        $this->addColumn('attachments','bind','varchar(255) NULL');
    }

    public function down()
    {
        $this->dropColumn('attachments','bind');
    }
}
