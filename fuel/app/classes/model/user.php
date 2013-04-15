<?php

class Model_User extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'chara',
		'icon_name' => array('default' => ''),
		'icon_ext'  => array('default' => ''),
		'tw_id',
		'tw_token',
		'tw_token_secret',
		'fav_tenpo',
		'fav_tenpo_game',
		'fav_tenpo_2',
		'fav_tenpo_game_2',
		'fav_tenpo_3',
		'fav_tenpo_game_3',
		'remote_addr',
		'user_agent',
		'input_date',
		'input_datetime',
		'update_date',
		'update_datetime',
	);

	protected static $_table_name = 'users';
/*
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events'          => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events'          => array('before_save'),
			'mysql_timestamp' => true,
		),
		'MyOrm\Observer_NextVal' => array(
			'events' => array('before_insert'),
			'key'    => 'accounts_seq',
		),
	);
*/
/*
	protected static $_has_one = array(
		// relation to user_profiles
		'profile' => array(
			'key_from'       => 'id',
			'model_to'       => 'Model_User_Profile',
			'key_to'         => 'uid',
			'cascade_save'   => true,
			'cascade_delete' => true,
		),
		// relation to circle_member
		'circle_member' => array(
			'key_from'       => 'id',
			'model_to'       => 'Model_Circle_Member',
			'key_to'         => 'uid',
			'cascade_save'   => false,
			'cascade_delete' => true,
		),
	);
*/
	public static function cnt_email($email)
	{
		return static::find()
			->where('email', $email)
			->count();
	}

	public static function find_by_email($email)
	{
		return static::find()
			->where('email', $email)
			->get_one();
	}

	public static function find_one_by_tw_id($tw_id)
	{
		return static::find()
			->where('tw_id', $tw_id)
			->get_one();
	}

	public static function is_internal_account($id)
	{
		$user = static::find($id);
		if ($user->email && $user->password)
		{
			return true;
		}

		return false;
	}

	public static function find_user($id)
	{
		return static::find($id, array('related' => 'id'));
	}

	public static function get_with_relation_by_id($id)
	{
		return static::find()
			->where('id', $id)
		//	->related('name')
		//	->related('tw_id')
			->get_one();
	}
	
	public static function set_array_as_mst_user($keyword)
	{
		$obj_arr = static::find()
							->where('name',"like","%".$keyword."%")
							->or_where('id',"=",(int)$keyword)
							->get();
		$ret = array();
		foreach($obj_arr as $obj){
			array_push($ret, $obj->to_array());
		}
		return $ret;
	}
}
