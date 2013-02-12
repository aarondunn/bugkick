<?php

class m120819_050956_create_forum_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{forum}}', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'description' => 'string',
        ));
        $this->createIndex('forum_title', '{{forum}}', 'title');
	}

	public function down()
	{
        $this->dropTable('{{forum}}');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}