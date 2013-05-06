
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

	/**
	* 店舗毎のチェックインユーザー数を取得する
	*
	* @param Array $tenpo_and_games 取得対象のtenpo_idとgameのセット配列
	* @param Array $datetime_range 到着予定時間の範囲
	* @return Array 取得した店名(name)とユーザー数(cnt_user)の配列
	*/
	public static function get_checkin_summary($tenpo_and_games, $datetime_range)
	{
		$entries = array();

		// TODO: 書き直したい一発で店舗名とユーザー数を取ってくるSQL、ormでうまく書く方法が思いつかなかった
		$sql = <<<EOF
SELECT `gc`.`name`, `gc`.`id`, count(ck.tenpo_id) as cnt_user, :game as game
FROM `gamecenter` AS `gc` 
LEFT JOIN (
			select ck.tenpo_id
			from checkin as ck
			where ck.game = :game
			and ck.tenpo_id = :tenpo_id
			and ck.checkin_datetime between :checkin_datetime_start and :checkin_datetime_end
) AS `ck` ON (`ck`.`tenpo_id` = `gc`.`id`) 
WHERE `gc`.`id` = :tenpo_id
GROUP BY `ck`.`tenpo_id` 
LIMIT 1
EOF;
		if(!array_key_exists('start', $datetime_range) || !array_key_exists('end', $datetime_range)) {
			return $entries;
		}

		foreach($tenpo_and_games as $tenpo_game){
			if(!array_key_exists('tenpo_id', $tenpo_game) || !array_key_exists('game', $tenpo_game)) continue;
			if(is_null($tenpo_game['tenpo_id']) || is_null($tenpo_game['game'])) continue;
			$query = DB::query($sql);
			$query->parameters(array(
				'tenpo_id' => $tenpo_game['tenpo_id'],
				'game' => $tenpo_game['game'],
				'checkin_datetime_start' => $datetime_range['start'],
				'checkin_datetime_end' => $datetime_range['end']
			));
			$result = $query->execute()->current();
			$entries[] = $result;
			//$entry = $result->as_array();
			//if(count($entry)) $entries[] = $entry[0];
		}

		return $entries;
	}
}
