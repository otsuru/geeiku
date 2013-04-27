<?php
/**
 * File: twitter.php
 * Created: 2012-03-22 16:42
 * Author: hrfmsd
 * Ver:
 */

namespace Twitter;

set_include_path(get_include_path() . PATH_SEPARATOR . PKGPATH);
require_once 'HTTP/OAuth/Consumer.php';
require_once 'Services/Twitter.php';

class Twitter
{
		
	private $_tokens = array(
		'consumer_key' => null,
		'consumer_secret' => null,
		'access_token' => null,
		'access_token_secret' => null,
	);


	public function __construct()
	{
		$config = \Config::load('twitter', true);

		$this->_tokens['consumer_key'] = $config['consumer_key'];
		$this->_tokens['consumer_secret'] = $config['consumer_secret'];
		
		$this->_callback = $config['callback'];
		// TODO: login chk
		/*
		if (\Session::get('access_token') !== null && \Session::get('access_token_secret'))
		{
			$this->_tokens['access_token'] = \Session::get('access_token');
			$this->_tokens['access_token_secret'] = \Session::get('access_token_secret');
		}
		 * /
		 */
		
		$this->_consumer = $this->getInstanceOfOAuth(
			$this->_tokens['consumer_key'],
			$this->_tokens['consumer_secret'],
			$this->_tokens['access_token'],
			$this->_tokens['access_token_secret']
		);
		
		$http_request = new \HTTP_Request2();
		$http_request->setConfig('ssl_verify_peer', false);

		$consumer_request = new \HTTP_OAuth_Consumer_Request;
		$consumer_request->accept($http_request);
	}

	protected function getInstanceOfOAuth($key, $secret, $token = null, $tokenSecret = null)
	{
		return new \HTTP_OAuth_Consumer($key, $secret, $token, $tokenSecret);
	}

	public function auth()
	{
		if ($this->_consumer->getToken() !== null && $this->_consumer->getTokenSecret() !== null)
		{
			//\Response::redirect(SITE_URL."login/callback");
		}
		$this->_consumer->getRequestToken('http://api.twitter.com/oauth/request_token', $this->_callback);

		\Session::set('request_token', $this->_consumer->getToken());
		\Session::set('request_token_secret', $this->_consumer->getTokenSecret());

		// 認証用URLを取得
//		$auth_url = $this->_consumer->getAuthorizeUrl('https://api.twitter.com/oauth/authorize');
		$auth_url = $this->_consumer->getAuthorizeUrl('https://api.twitter.com/oauth/authenticate');
		\Response::redirect($auth_url);
	}

	public function get_callback_auth($verifier)
	{
		// リクエストトークンを使ってアクセストークンを取得
		$this->_consumer->setToken(\Session::get('request_token'));
		$this->_consumer->setTokenSecret(\Session::get('request_token_secret'));
		$this->_consumer->getAccessToken('http://api.twitter.com/oauth/access_token', $verifier);

		\Session::set('access_token', $this->_consumer->getToken());
		\Session::set('access_token_secret', $this->_consumer->getTokenSecret());
//\Debug::dump($this->_consumer);

		$services_twitter = new \Services_Twitter();
		$services_twitter->setOAuth($this->_consumer);

		$tw_account = $services_twitter->account->verify_credentials();

		/**
		 * ユーザー登録確認
		 */
		$chk_user = \Model_User::find_one_by_tw_id($tw_account->id);
		/**
		 * ログイン情報格納 DB::users, user_profile
		 */
		
//		if ($chk_user->id)
		if(!(is_null($chk_user)))
		{
			\Session::set('user_id', $chk_user->id);
			$user = $chk_user;
			$tw_account->name = $chk_user->name;
			
			
			$tw_icon_url = $tw_account->profile_image_url;
			preg_match(':/.+\.(.+)$:', $tw_icon_url, $matches);
			$tw_icon_ext = $matches[1];
			$tw_icon_filename = md5(serialize($tw_icon_url));
			$tw_icon = file_get_contents('https://api.twitter.com/1/users/profile_image?screen_name='.
			$tw_account->screen_name.'&size=bigger');

			// ファイル保存
			@mkdir(USER_PROFILE_IMG_PATH.$user->id, 0755, true);
			$profile_image_file = USER_PROFILE_IMG_PATH.$user->id.'/'.$tw_icon_filename.'.'.$tw_icon_ext;
			file_put_contents($profile_image_file, $tw_icon);

			$account = \Model_User::find_user($user->id);
			$account->icon_name = $tw_icon_filename;
			$account->icon_ext = $tw_icon_ext;
			$account->save();
			
		}
		else
		{
			$user = new \Model_User();
			$user->name = $tw_account->name;
			$user->tw_id = $tw_account->id;

		}
		$user->tw_token = $this->_consumer->getToken();
		$user->tw_token_secret = $this->_consumer->getTokenSecret();
		$user->remote_addr = \Input::ip();
		$user->input_date = CNF_NOW_DATE;
		$user->input_datetime = CNF_NOW_DATETIME;
		$user->save();

		if (is_null($chk_user))
		{
			/*
			$user_profile = new \Model_User_Profile();
			$user_profile->uid = $user->id;
			$user_profile->remote_addr = \Input::ip();
			$user_profile->save();
			*/
			/**
			 * プロフィール画像登録
			 */
			$tw_icon_url = $tw_account->profile_image_url;
			preg_match(':/.+\.(.+)$:', $tw_icon_url, $matches);
			$tw_icon_ext = $matches[1];
			$tw_icon_filename = md5(serialize($tw_icon_url));
			$tw_icon = file_get_contents('https://api.twitter.com/1/users/profile_image?screen_name='.
			$tw_account->screen_name.'&size=bigger');

			// ファイル保存
			@mkdir(USER_PROFILE_IMG_PATH.$user->id, 0755, true);
			$profile_image_file = USER_PROFILE_IMG_PATH.$user->id.'/'.$tw_icon_filename.'.'.$tw_icon_ext;
			file_put_contents($profile_image_file, $tw_icon);

			// リサイズ
			/*
			\Image::load($profile_image_file)
				->preset('l')
				->save_pa(null, '_l');

			\Image::load($profile_image_file)
				->preset('m')
				->save_pa(null, '_m');

			\Image::load($profile_image_file)
				->preset('s')
				->save_pa(null, '_s');
/*
			$profile_image = new \Model_User_Profile_Image();
			$profile_image->uid = $user->id;
			$profile_image->filename = $tw_icon_filename;
			$profile_image->ext = $tw_icon_ext;
			$profile_image->save();
*/
			$account = \Model_User::find_user($user->id);
			$account->icon_name = $tw_icon_filename;
			$account->icon_ext = $tw_icon_ext;
			$account->save();
			
			
			$chk_user = \Model_User::find_one_by_tw_id($tw_account->id);
			\Session::set('user_id', $chk_user->id);
			
		}

		/**
		 * セッション処理
		 */
		//$auth = new \Auth();
		//$auth->set_auth($user->id, true);

		return $tw_account;
	}
	
}
