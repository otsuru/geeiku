<?php
/**
 * APPPATH/classes/controller/search.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/01/14
 */
class Controller_Search extends Controller_Template
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
		$this->template->header = View::forge('default/header');
		$this->template->footer = View::forge('default/footer');
		$this->template->content = View::forge('search/index',$data);
		
		$select_list = \Model_Gamecenter::set_array_as_mst();
        $this->template->content->set('select_list', $select_list);   
	}
	
	public function action_form()
	{			
		//マイページからの検索
		$this->template->header = View::forge('default/header');
		$this->template->footer = View::forge('default/footer');
		$this->template->content = View::forge('search/index');
		$select_list = \Model_Gamecenter::set_array_as_mst();
        $this->template->content->set('select_list', $select_list);
        
		if( \Session::get('tenpo_id') != "" && \Session::get('game_id') != ""){
			
			$tenponame = \Model_Gamecenter::find_by_tenponame(\Session::get('tenpo_id'));
			if(!is_null($tenponame)){
				$tenponame = $tenponame->name;
			}else{
				$tenponame = 0;
			}
			$userdata = \Model_Checkin::set_array_as_mst(\Session::get('tenpo_id'),\Session::get('game_id'));
			$this->template->content->set('userdata', $userdata);
			$this->template->content->set('tenponame', $tenponame);
		}
	}
	
	public function action_search()
	{	
		//検索ページからの検索
		$val = $this->_validation();

		//エラーチェック
		if (!$val->run())
		{
			$this->_data['errors']   = $val->error();
			$this->template->header = View::forge('default/header');
			$this->template->footer = View::forge('default/footer');
			$this->template->content = \View::forge('search/index', $this->_data);
			
			$select_list = \Model_Gamecenter::set_array_as_mst();
		    $this->template->content->set('select_list', $select_list);
			$this->template->content->set_safe('html_error', $val->show_errors());
		}
		else
		{
			$valid                = $val->validated();
			$this->_data['input'] = $val->input();
			$this->template->header = View::forge('default/header');
			$this->template->footer = View::forge('default/footer');
			$this->template->content = \View::forge('search/index', $this->_data);
			
			if($valid["keyword"] != "" && is_numeric($valid["game"])){
				$userdata = \Model_Checkin::set_array_as_mst_user($valid["keyword"],$valid["game"]);
				if(isset($userdata["0"]["tenpo_id"])){
					$tenponame = \Model_Gamecenter::find_by_tenponame($userdata["0"]["tenpo_id"]);
					if(!is_null($tenponame)){
						$tenponame = $tenponame->name;
					}else{
						$tenponame = 0;
					}
					$this->template->content->set('tenponame', $tenponame);
					$this->template->content->set('userdata', $userdata);
				}
				else{
					$this->template->content->set_safe('html_error', "登録データが見つかりませんでした。");
				}
			}
			elseif(is_numeric($valid["tenpo"]) && is_numeric($valid["game"])){
				$tenponame = \Model_Gamecenter::find_by_tenponame($valid["tenpo"]);
				$tenponame = $tenponame->name;
				$this->template->content->set('tenponame', $tenponame);
				$userdata = \Model_Checkin::set_array_as_mst($valid["tenpo"],$valid["game"]);
				$this->template->content->set('userdata', $userdata);
			}
			//ユーザー検索
			elseif($valid["keyword"] != ""){
				$userdata_one = \Model_User::set_array_as_mst_user($valid["keyword"]);
				if(isset($userdata_one["0"]["name"])){
					$this->template->content->set('userdata_one', $userdata_one);
				}
				else{
					$this->template->content->set_safe('html_error', "ユーザーが見つかりませんでした。");
				}
			}
			else{
				$this->template->content->set_safe('html_error', "登録データが見つかりませんでした。");
			}
			
			$select_list = \Model_Gamecenter::set_array_as_mst();
			$this->template->content->set('select_list', $select_list);
		}
	}
	
	
	protected function _validation()
	{
		$val = Validation::forge();
		
        $val->add('tenpo', '店舗');
//			->add_rule('required')
//			->add_rule('valid_string','numeric');
        $val->add('game', 'ゲーム');
//			->add_rule('required')
//			->add_rule('valid_string','numeric');
		$val->add('keyword', 'キーワード')
			->add_rule('max_length', 50);

		return $val;
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
		$ret[14] = 'ストリートファイターZERO3';
		$ret[97] = 'パワーストーン';
		$ret[98] = 'スト2レインボー';
		$ret[99] = '家庭用';
		$ret[100] = '音ゲー';
		
		return $ret;
	}
	
	public static function first_statas($user_id){
		
		$account = \Model_User::get_with_relation_by_id($user_id);
		
		if(is_null($account)){
			\Response::redirect(SITE_URL.'logout');
		}
		
		//初期値セット
		$data = array();
		$data['tenpo'] = $account->fav_tenpo;
		$data['game'] = $account->fav_tenpo_game;
		
		return $data;
	}
	
	
}

