<?php

use yii\db\Migration;

class m160918_214111_add_files_tbl extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'path' => $this->string()->notNull(),
            'ext' => $this->string(10)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('{{%attachments}}', [
            'id' => $this->primaryKey(),
            'group' => $this->string()->notNull(),
            'file_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'is_main' => $this->smallInteger()->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%files}}');
        $this->dropTable('{{%attachments}}');
    }
}
