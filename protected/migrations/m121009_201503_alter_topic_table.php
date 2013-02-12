<?php

class m121009_201503_alter_topic_table extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{topic}}','topic_starter_id', 'INT UNSIGNED NOT NULL');
        $this->createIndex('topic_starter_id', '{{topic}}', 'topic_starter_id');
	}

	public function down()
	{
        $this->dropColumn('{{topic}}','topic_starter_id');
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