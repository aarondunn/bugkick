<?php

class m121110_114253_alter_table_user extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{user}}','forum_role', 'VARCHAR( 50 ) NOT NULL DEFAULT  \'user\'');
        $this->createIndex('forum_role', '{{user}}', 'forum_role');
	}

	public function down()
	{
        $this->dropColumn('{{user}}','forum_role');
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