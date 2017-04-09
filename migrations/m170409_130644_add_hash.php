<?php

use yii\db\Migration;

class m170409_130644_add_hash extends Migration
{
    public function up()
    {
        $this->addColumn('attachments', 'hash', 'varchar(255) NULL');
    }

    public function down()
    {
        $this->dropColumn('attachments', 'hash');
    }
}
