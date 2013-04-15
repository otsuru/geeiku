<?php
/**
 * APPPATH/classes/controller/login.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/01/14
 */
class Controller_Login extends \Fuel\Core\Controller
{	
	
	public function before()
	{
		parent::before();

		\Package::load('twitter');
		
	}

	public function action_index()
	{
		
		if(!isset($_COOKIE["fuelcid"])){
			$_COOKIE["fuelcid"] = "";
		}
		if($_COOKIE["fuelcid"] != "" && \Session::get('user_id') != ""){
			\Response::redirect(SITE_URL.'users');
		}else{
			return Response::forge(View::forge('login/index'));
		}
		exit;
	}
	public function action_form()
	{
		// CSRF 対策
		if ( ! Security::check_token()){
			return 'ページ遷移が正しくありません。';
			exit;
		}
		
		$twitter = new \Twitter\Twitter();
		$twitter->auth();
	}
	
	public function action_callback()
	{
		$twitter = new \Twitter\Twitter();
		$account = $twitter->get_callback_auth(\Input::get('oauth_verifier'));
		\Response::redirect(SITE_URL.'users');
		
	}
}

