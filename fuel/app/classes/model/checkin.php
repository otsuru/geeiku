
<?php

class Model_Checkin extends \Orm\Model
//class Model_Checkin extends Model_Crud
{
	protected static $_properties = array(
		'id',
		'tenpo_id',
		'user_id',
		'user_name',
		'chara',
		'game',
		'checkin_datetime',
		'comment',
		'iine_name',
		'iine_id',
	);

	protected static $_table_name = 'checkin';
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

	protected static $_has_one = array(
		// relation to user_profiles
		'profile' => array(
			'key_from'       => 'user_id',
			'model_to'       => 'Model_User',
			'key_to'         => 'id',
			'cascade_save'   => false,
			'cascade_delete' => false,
		),
	);

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
		return static::find()
			->where('user_id', (int)$id)
			->get_one();
	}

	public static function find_userid($id)
	{
		return static::find((int)$id, array('related' => 'user_id'));
	}
	
	public static function set_array_as_mst_user($keyword,$game)
	{
		$obj_arr = static::find()
							->where('user_name',"like","%".$keyword."%")
							->where('game',"=",(int)$game)
							->related('profile')
							->get();
		$ret = array();
		foreach($obj_arr as $obj){
			array_push($ret, $obj->to_array());
		}
		return $ret;
	}
	
	public static function set_array_as_mst($id,$game)
	{
		$obj_arr = static::find()
							->where('tenpo_id',"=",(int)$id)
							->where('game',"=",(int)$game)
							->related('profile')
							->get();
		$ret = array();
		foreach($obj_arr as $obj){
			array_push($ret, $obj->to_array());
		}
		return $ret;
	}
	
	public static function delete_by_uid($uid)
	{
		return static::find()
			->where('user_id', $uid)
			->delete();
	}
	public static function delete_by_users()
	{
		$date = date('Y-m-d H:i:s');
		$after = date("Y-m-d H:i:s",strtotime("-8 hours" ,strtotime($date)));
		return static::find()
			->where('checkin_datetime',"<",$after)
			->delete();
	}
}
