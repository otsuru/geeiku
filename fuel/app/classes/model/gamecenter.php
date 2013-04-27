<<<<<<< HEAD

<?php

class Model_Gamecenter extends \Orm\Model
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

	protected static $_table_name = 'gamecenter';
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
		$obj_arr = static::find()->order_by('pref','asc')->order_by('name','asc')->get();
		//$ret = array(array('pref' => '0',array('id' => '0', 'name' => '-----')));
		$ret = array(array('' => '-----'));           
$pref = array(
1 => '北海道',
2 => '青森県',
3 => '岩手県',
4 => '宮城県',
5 => '秋田県',
6 => '山形県',
7 => '福島県',
8 => '茨城県',
9 => '栃木県',
10 => '群馬県',
11 => '埼玉県',
12 => '千葉県',
13 => '東京都',
14 => '神奈川県',
15 => '山梨県',
16 => '長野県',
17 => '新潟県',
18 => '富山県',
19 => '石川県',
20 => '福井県',
21 => '岐阜県',
22 => '静岡県',
23 => '愛知県',
24 => '三重県',
25 => '滋賀県',
26 => '京都府',
27 => '大阪府',
28 => '兵庫県',
29 => '奈良県',
30 => '和歌山県',
31 => '鳥取県',
32 => '島根県',
33 => '岡山県',
34 => '広島県',
35 => '山口県',
36 => '徳島県',
37 => '香川県',
38 => '愛媛県',
39 => '高知県',
40 => '福岡県',
41 => '佐賀県',
42 => '長崎県',
43 => '熊本県',
44 => '大分県',
45 => '宮崎県',
46 => '鹿児島県',
47 => '沖縄県'
);

		foreach($obj_arr as $obj){
                        $ret[$pref[$obj["pref"]]][$obj["id"]] = $obj["name"];
		}
		return $ret;
	}
}
=======

<?php

class Model_Gamecenter extends \Orm\Model
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

	protected static $_table_name = 'gamecenter';
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
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
