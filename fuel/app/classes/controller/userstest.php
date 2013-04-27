<?php
/**
 * APPPATH/classes/controller/users.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/04/11
 * testfile
 */

set_include_path(get_include_path() . PATH_SEPARATOR . PKGPATH);
require_once 'HTTP/OAuth/Consumer.php';
require_once 'Services/Twitter.php';

class Controller_Userstest extends Controller_Template
{	
	
	 public function before()
    {
		parent::before();
		\Package::load('twitter');
		
		if(\Session::get('user_id') == ""){
			\Response::redirect(SITE_URL.'login');
		}
		
    }
	public function action_index()
	{	
		//初期値セット
		$data = $this->first_statas(\Session::get('user_id'));
		//テンプレート呼び出し
		$this->template->header = View::forge('default/header');
		$this->template->footer = View::forge('default/footer');
		$this->template->content = View::forge('userstest/index', $data);
		
		$select_list = \Model_Gamecentertest::set_array_as_mst();
                $this->template->content->set('select_list', $select_list);
		
		if($data['fav_tenpo'] != "" && $data['fav_tenpo_game'] != ""){
			$tenpo_u_data = $this->fav_tenpo_entry_user($data['fav_tenpo'],$data['fav_tenpo_game']);
		    $this->template->content->set('tenpo_u_data', $tenpo_u_data);
		}
	}
	
	public function action_form()
	{		
		// CSRF 対策
		/*
		if ( ! Security::check_token()){
			return 'ページ遷移が正しくありません。';
			exit;
		}
*/
		//初期値セット
		$data = $this->first_statas(\Session::get('user_id'));
		//テンプレート呼び出し
		$this->template->header = View::forge('default/header');
		$this->template->footer = View::forge('default/footer');
		$this->template->content = View::forge('userstest/index', $data);
		
		$select_list = \Model_Gamecenter::set_array_as_mst();
                $this->template->content->set('select_list', $select_list);
		
		if($data['fav_tenpo'] != "" && $data['fav_tenpo_game'] != ""){
			$tenpo_u_data = $this->fav_tenpo_entry_user($data['fav_tenpo'],$data['fav_tenpo_game']);
			$this->template->content->set('tenpo_u_data', $tenpo_u_data);
		}
		$val = $this->_validation();
		
		//サブミットボタン定義
		$submit = Input::post('submit');
		$entry = Input::post('entry');
		$search = Input::post('search');
		$checkin = Input::post('checkin');
		$checkin_hide = Input::post('checkin_hide');
		$reset = Input::post('reset');
		
		if (!$val->run())
		{
			$data['errors']   = $val->error();
		}
		else
		{
			$valid                = $val->validated();
			$this->_data['input'] = $val->input();
			/**
			 * DB処理
			 */
			//検索
			if($submit != ""){
				try
				{	
					\Session::set('tenpo_id', $valid['search_tenpo']);
					\Session::set('game_id', $valid['search_game']);


					if(!(is_numeric($valid["search_tenpo"]) && is_numeric($valid["search_game"]))){
						$this->template->content->set('message', "誰かいるかな？検索は店舗、ゲームを選択してください");
					}
					else{
						\Response::redirect(SITE_URL.'search/form');
					}
				}
				catch (\Database_Exception $e)
				{
					$error = "エラー";
					\Debug::dump($error);
					//throw new HttpApplicationErrorException($e->getMessage());
				}
			}
			//お気に入り店舗登録&更新
			elseif($entry != ""){
				try
				{
					$users = Model_User::find($data['id']);
					$users->fav_tenpo = $valid['fav_tenpo'];
					$users->fav_tenpo_game = $valid['fav_tenpo_game'];
					$users->save();

					\Response::redirect(SITE_URL.'userstest');
				}
				catch (\Database_Exception $e)
				{
					$error = "エラー";
					return $error;
					//throw new HttpApplicationErrorException($e->getMessage());
				}
			}

			//予約
			elseif($checkin != "" || $checkin_hide != ""){
				try
				{
					$checkin_new = Model_Checkin::find_user($data['id']);
				}
				catch (\Database_Exception $e)
				{
					$error = "データがない";
					//throw new HttpApplicationErrorException($e->getMessage());
				}

				if(is_null($checkin_new)){
					$checkin_new = new Model_Checkin();
				}
				else{
					$checkin_new = \Model_Checkin::find_userid($checkin_new->id);
				}
				try
				{
					$checkin_new->tenpo_id = $valid["checkin_tenpo"];
					$checkin_new->user_id = $data['id'];
					$checkin_new->user_name = $data['name'];
					$checkin_new->game = $valid['game'];
                                        if($valid['comment'] != "時間やコメントなど"){
                                            $checkin_new->comment = $valid['comment'];
                                        }else{
                                            $checkin_new->comment = "";
                                        }
                                        if($valid["showtime"] == "on"){
                                            $checkin_new->checkin_datetime = $valid["user_date_year"]."-".$valid["user_date_m"]."-".$valid["user_date_d"]." ".$valid["user_time_h"].":".$valid["user_time_m"];
                                        }else{
                                            $checkin_new->checkin_datetime = DATE_Y."-".DATE_M."-".DATE_D." 23:59";
                                        }
                                        if($checkin_hide != ""){
                                            $checkin_new->hide = 1;
                                        }else{
                                            $checkin_new->hide = 0;
                                        }
					if($valid['checkin_tenpo'] != $data['tenpo_id']){
						$checkin_new->iine_id = "";
						$checkin_new->iine_name = "";
					}
					$checkin_new->save();

					//Twitter連動
					if($valid["twit_connect"] == "on"){
						try {  
							$twitter = new Services_Twitter();  
							$oauth   = new HTTP_OAuth_Consumer('dlprZm1Ayqvg5pbvds0I3Q',  
															   'lGR9uQ8NAZB2nwdUuskT96f14oPNYV7IE8hSRqLQ',  
															   \Session::get('access_token'),  
															   \Session::get('access_token_secret'));  
							$twitter->setOAuth($oauth);
							$gamelist = $this->arr_game();
							$msg = $twitter->statuses->update($select_list[$valid["checkin_tenpo"]]."(".$valid["user_date_m"]."月".$valid["user_date_d"]."日 ".$valid["user_time_h"].":".$valid["user_time_m"]."着予定) ".$valid['comment']." #ゲー行く_".$gamelist[$valid["game"]]." http://bit.ly/Yo64Uj"); 
						} catch (Services_Twitter_Exception $e) {  
							echo $e->getMessage();  
						}				
					}
					\Response::redirect(SITE_URL.'userstest');
				}
				catch(\Database_Exception $e){
					$error = "エラー";
					return $error;
					exit;
				}

			}
			//削除
			elseif($reset != ""){
				try
				{
					//削除するデータを検索
					$delete = Model_Checkin::delete_by_uid($data['id']);
				}
				catch (\Database_Exception $e)
				{
					$error = "データがない";
					//throw new HttpApplicationErrorException($e->getMessage());
				}
				\Response::redirect(SITE_URL.'userstest');
			}
				
		}
	}
	
	protected function _validation()
	{
		$val = Validation::forge();
		
        $val->add('fav_tenpo', 'お気に入り店舗');
        $val->add('fav_tenpo_game', 'お気に入りゲーム');
        $val->add('search_tenpo', '検索店舗')
		->add_rule('required');
        $val->add('search_game', '検索ゲーム')
		->add_rule('required');
		$val->add('checkin_tenpo', '予約店舗');
		
		$val->add('twit_connect', 'ツイッター連動');
                $val->add('showtime', '時間詳細');
		$val
			->add('user_date_year', '予約年');
		$val
			->add('user_date_m', '予約月');
		$val
			->add('user_date_d', '予約日');

		$val
			->add('user_time_h', '時間（時）');
		$val
			->add('user_time_m', '時間（分）');
		$val
			->add('game', 'ゲーム');
		$val
			->add('comment', 'コメント')
			->add_rule('max_length', 100);

		return $val;
	}
	
	public static function arr_y()
	{
		$ret    = array();
		//$ret[0] = '-';
		for ($i = (int)DATE_Y; $i < ((int)DATE_Y) + 1; $i++)
		{
			$ret[0] = $i;
		}
		return $ret;
	}

	public static function arr_m()
	{
		$ret   = array();
		$ret[] = '-';
		for ($i = 1; $i <= 12; $i++)
		{
			$ret[$i] = sprintf('%02d', $i);
		}
		return $ret;
	}

	public static function arr_d()
	{
		$ret   = array();
		$ret[] = '-';
		for ($i = 1; $i <= 31; $i++)
		{
			$ret[$i] = sprintf('%02d', $i);
		}
		return $ret;
	}

	public static function arr_time()
	{
		$ret   = array();
		$ret[] = '-';
		for ($i = 1; $i <= 24; $i++)
		{
			$ret[$i] = sprintf('%02d', $i);
		}
		return $ret;
	}

	public static function arr_min()
	{
		$ret   = array();
		$ret[] = '-';
		for ($i = 0; $i <= 60; $i = $i + 10)
		{
			$ret[$i] = sprintf('%02d', $i);
		}
		return $ret;
	}

	public static function arr_game()
	{
		$ret   = array();
		$ret[] = '-';
		
		$ret[1] = 'GGXXACPR';
		$ret[2] = 'BBCP';
		$ret[3] = 'P4U';
		$ret[4] = 'UNI';
		$ret[5] = 'アルカナハート3';
		$ret[6] = 'アクアパッツァ';
		$ret[7] = 'ヴァンパイアセイヴァー';
		$ret[8] = 'KOF13クライマックス';
		$ret[9] = 'エヌアイン完全世界';
		$ret[10] = '戦国BASARAX';
		$ret[11] = 'アカツキ電光戦記';
		$ret[12] = 'カオスコード';
		$ret[13] = '鉄拳TAG2';
		$ret[97] = 'パワーストーン';
		$ret[98] = 'スト2レインボー';
		$ret[99] = '家庭用';
		$ret[100] = '音ゲー';
		
		return $ret;
	}
	
	public static function first_statas($user_id){
		
		$account = \Model_User::get_with_relation_by_id($user_id);
		$mycheckin = \Model_Checkin::find_user($user_id);
		if(is_null($account)){
			\Response::redirect(SITE_URL.'logout');
		}
		
		//初期値セット
		$data = array();
		$data['id'] = $account->id;
		$data['name'] = $account->name;
		$data['icon_name'] = $account->icon_name;
		$data['icon_ext'] = $account->icon_ext;
		$data['fav_tenpo'] = $account->fav_tenpo;
		$data['fav_tenpo_game'] = $account->fav_tenpo_game;
                
                if($account->fav_tenpo != ""){
        		$tenponame = \Model_Gamecentertest::find_by_tenponame($account->fav_tenpo);
                        $data["tenpo_name"] = $tenponame->name;
                }else{
                    $data["tenpo_name"] = "";
                }
		if(!is_null($mycheckin)){
			$data['tenpo_id'] = $mycheckin->tenpo_id;
			$data['game'] = $mycheckin->game;
			$data['comment'] = "時間やコメントなど";
			$data['yoyaku_flg'] = 1;
 		}else{
			$data['tenpo_id'] = $data['fav_tenpo'];
			$data['game'] = $data['fav_tenpo_game'];
			$data['comment'] = "時間やコメントなど";
			$data['yoyaku_flg'] = 0;
		}
		return $data;
	}
	
	public static function fav_tenpo_entry_user($tenpo_id,$game_id){

		$userdata = \Model_Checkin::set_array_as_mst($tenpo_id,$game_id);
		return $userdata;
	}


}

