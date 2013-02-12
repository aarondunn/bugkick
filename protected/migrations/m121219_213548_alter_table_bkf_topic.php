<?php

class m121219_213548_alter_table_bkf_topic extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{topic}}','archived', 'TINYINT NOT NULL');
        $this->createIndex('archived', '{{topic}}', 'archived');
	}

	public function down()
	{
        $this->dropColumn('{{topic}}','archived');
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