<?php

class m120819_052719_create_post_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{post}}', array(
            'id' => 'pk',
            'time'=>'datetime NOT NULL',
            'body' => 'text NOT NULL',
            'topic_id'=>'integer NOT NULL',
            'user_id'=>'integer NOT NULL',
        ));
        $this->createIndex('post_time', '{{post}}', 'time');
        $this->addForeignKey('fk_topic',
                '{{post}}', 'topic_id',
                '{{topic}}', 'id',
                'CASCADE');
        
//        $this->addForeignKey('fk_user',
//                '{{post}}', 'user_id',
//                '{{user}}', 'id',
//                'CASCADE');
	}

	public function down()
	{
		$this->dropTable('{{post}}');
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