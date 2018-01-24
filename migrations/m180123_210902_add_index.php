<?php

use yii\db\Migration;

/**
 * Class m180123_210902_add_index
 */
class m180123_210902_add_index extends Migration
{
    public function up()
    {
        $this->addColumn('attachments', 'index', 'int(6) DEFAULT 0');
    }

    public function down()
    {
        $this->dropColumn('attachments', 'index');
    }
}
