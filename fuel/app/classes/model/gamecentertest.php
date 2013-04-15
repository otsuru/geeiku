
<?php

class Model_Gamecentertest extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'pref',
		'address',
		'tel',
		'zip',
		'open_time',
		'game',
		'info',
		'input_date',
		'input_datetime',
		'update_date',
		'update_datetime',
	);

	protected static $_table_name = 'gamecentertest';
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

	public static function find_by_tenponame($id)
	{
		return static::find()
			->where('id', (int)$id)
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
	
	public static function set_array_as_mst()
	{
		$obj_arr = static::find()->order_by('pref','asc')->get();
		$ret = array(array('id' => '0', 'name' => '-----'));
		$tenponame = array();
		foreach($obj_arr as $obj){
			array_push($ret, $obj->to_array());
		}
		foreach($ret as $key){
			$tenponame[$key["id"]] = $key["name"];
		}
		return $tenponame;
	}
}
