<<<<<<< HEAD
<?php

namespace Fuel\Migrations;

class Create_Gamecenter
{
	public function up()
	{
		\DBUtil::create_table('gamecenter', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'icon_name' => array('constraint' => 32, 'type' => 'varchar'),
			'icon_ext' => array('constraint' => 5, 'type' => 'varchar'),
			'email' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'password' => array('constraint' => 64, 'type' => 'varchar', 'null' => true),
			'tw_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'tw_token' => array('constraint' => 128, 'type' => 'varchar', 'null' => true),
			'tw_token_secret' => array('constraint' => 128, 'type' => 'varchar', 'null' => true),
			'remote_addr' => array('constraint' => 39, 'type' => 'varchar'),
			'created_at' => array('type' => 'timestamp'),
			'updated_at' => array('type' => 'timestamp'),

		), array('id'));

		\Dbutil::create_index('users', 'email', 'uk_email', 'unique');
		\Dbutil::create_index('users', 'tw_id', 'uk_tw_id', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
=======
<?php

namespace Fuel\Migrations;

class Create_Gamecenter
{
	public function up()
	{
		\DBUtil::create_table('gamecenter', array(
			'id' => array('constraint' => 11, 'type' => 'int'),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'icon_name' => array('constraint' => 32, 'type' => 'varchar'),
			'icon_ext' => array('constraint' => 5, 'type' => 'varchar'),
			'email' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'password' => array('constraint' => 64, 'type' => 'varchar', 'null' => true),
			'tw_id' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'tw_token' => array('constraint' => 128, 'type' => 'varchar', 'null' => true),
			'tw_token_secret' => array('constraint' => 128, 'type' => 'varchar', 'null' => true),
			'remote_addr' => array('constraint' => 39, 'type' => 'varchar'),
			'created_at' => array('type' => 'timestamp'),
			'updated_at' => array('type' => 'timestamp'),

		), array('id'));

		\Dbutil::create_index('users', 'email', 'uk_email', 'unique');
		\Dbutil::create_index('users', 'tw_id', 'uk_tw_id', 'unique');
	}

	public function down()
	{
		\DBUtil::drop_table('gamecenter');
	}
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
}