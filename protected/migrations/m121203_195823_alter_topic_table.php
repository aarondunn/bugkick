<?php

class m121203_195823_alter_topic_table extends CDbMigration
{
    public function up()
   	{
           $this->addColumn('{{topic}}','time', 'DATETIME NOT NULL');
   	}

   	public function down()
   	{
           $this->dropColumn('{{topic}}','time');
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