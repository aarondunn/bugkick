<?php

class m120819_051610_create_topic_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{topic}}', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'description' => 'string',
            'forum_id'=>'integer NOT NULL'
        ));
        $this->createIndex('topic_title', '{{topic}}', 'title');
        $this->addForeignKey('fk_forum',
                '{{topic}}', 'forum_id',
                '{{forum}}', 'id',
                'CASCADE');
	}

	public function down()
	{
        $this->dropTable('{{topic}}');
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