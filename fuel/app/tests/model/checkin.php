<?php

class Test_Model_Checkin extends TestCase
{

	public function setup()
	{
		// TODO: 事前にsqldata/geeiku_test.sqlのテスト用DBにSQLぶっこむ
		// PDOオブジェクト取得してここで流すのが一番手間がないけど、
		// サクッとできなかったのでとりあえず手動で流しこんでからテスト
	}

	/**
	* 店舗へのチェックイン情報の簡易集計
	* 
	* @group Model
	* @group Checkin
	*/ 
	public function test_get_checkin_summary()
	{

		// 正常系
		$tenpo_and_games = array(
			array('tenpo_id' => 31, 'game' => 3),
			array('tenpo_id' => 32, 'game' => 3),
			array('tenpo_id' => 33, 'game' => 3)
		);
		$datetime_range = array(
			'start' => '2013-04-18 00:00:00',
			'end' => '2013-04-18 23:59:59',
		);
		$actual = \Model_Checkin::get_checkin_summary($tenpo_and_games,$datetime_range);
		$expected = array(
			array('name' => 'セガ秋葉原1号館', 'id' => 31, 'game' => 3, 'cnt_user' => 2),
			array('name' => 'CLUBSEGA新宿西口', 'id' => 32, 'game' => 3, 'cnt_user' => 1),
			array('name' => '新宿スポーツランド本館', 'id' => 33, 'game' => 3, 'cnt_user' => 0)
		);
		$this->assertEquals($expected, $actual, '予期しないデータ');


		// $tenpo_and_games(店舗用の配列)がたりない場合
		$tenpo_and_games = array(
			array('tenpo_id' => 31, 'game' => 3),
			array('game' => 3),
			array('tenpo_id' => NULL, 'game' => 3)
		);
		$expected = array(
			array('name' => 'セガ秋葉原1号館', 'id' => 31, 'game' => 3, 'cnt_user' => 2)
		);
		$actual = \Model_Checkin::get_checkin_summary($tenpo_and_games,$datetime_range);
		$this->assertEquals($expected, $actual, '予期しないデータ2');


		// $datetime_range(到着時刻)の配列情報が不足
		$tenpo_and_games = array(
			array('tenpo_id' => 31, 'game' => 3),
			array('tenpo_id' => 32, 'game' => 3),
			array('tenpo_id' => 33, 'game' => 3)
		);
		$datetime_range = array(
			'end' => '2013-04-18 23:59:59'
		);
		$actual = \Model_Checkin::get_checkin_summary($tenpo_and_games,$datetime_range);
		$expected = array();
		$this->assertEquals($expected, $actual, '予期しないデータ3');

	}
}