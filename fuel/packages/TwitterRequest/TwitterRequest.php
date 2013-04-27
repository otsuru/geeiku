<?php
/**
 * TwitterRequest
 *
 * PHP version 5.1.6+
 *
 * @version   1.0.0
 * @created   29/10/2010
 * @author    Haruaki Enokido @enok00
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://pear.php.net/package/Net_URL2/
 * @link      http://pear.php.net/package/HTTP_Request2/
 * @link      http://pear.php.net/package/HTTP_OAuth/
 * @link      http://github.com/meltingice/TwitPic-API-for-PHP
 */
 
require_once 'HTTP/OAuth/Consumer.php';

class TwitterRequest {
	const REQUEST_TOKEN_URL = 'http://twitter.com/oauth/request_token';
	const AUTHORIZE_URL = 'http://twitter.com/oauth/authorize?oauth_token=';
	const ACCESS_TOKEN_URL = 'http://twitter.com/oauth/access_token';
	
	protected $consumer = null;
	protected $consumer_key = null;
	protected $consumer_secret = null;
	protected $callback = null;
	
	/**
	 * コンストラクタ
	 */
	public function TwitterRequest($consumer_key, $consumer_secret, $callback=""){
		if(session_id()==""){ session_start(); }
		
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		if($callback!=""){ $this->callback = $callback; }
		
		$this->consumer = $this->createConsumer($this->consumer_key, $this->consumer_secret);
	}
	
	/**
	 * 再ビルド
	 */
	public static function build(){
		if(isset($_SESSION['tr_data'])){
			$data = unserialize($_SESSION['tr_data']);
			return new TwitterRequest($data->consumer_key, $data->consumer_secret);
		}else{
			print "1";
			exit;
		}
	}
	
	/**
	 * 初期実行
	 */
	public function run(){
		$this->getRequestToken($this->callback);
		
		$_SESSION['consumer_key'] = $this->consumer_key;  
		$_SESSION['consumer_secret'] =$this->consumer_secret;  
		$_SESSION['request_token'] = $this->consumer->getToken(); 
		$_SESSION['request_token_secret'] = $this->consumer->getTokenSecret();
		$_SESSION['tr_data'] = serialize($this);
		
		//var_dump($_SESSION['tr_data']);exit;
		
		$this->redirect(self::AUTHORIZE_URL.$this->consumer->getToken());
	}

	/**
	 * 再実行
	 */
	public function rerun(){
		//print "1";
		$this->setToken();

		return $this->consumer;
	}
	
	/**
	 * トークン設定
	 */
	private function setToken(){
		try{
			//print "1";
			if(!isset($_SESSION['access_token']) && !isset($_SESSION['access_token_secret'])){
				$oauth_verifier = $_GET['oauth_verifier'];
				
				$this->consumer->setToken($_SESSION['request_token']);
				$this->consumer->setTokenSecret($_SESSION['request_token_secret']);
				$this->consumer->getAccessToken(self::ACCESS_TOKEN_URL, $oauth_verifier);
			
				$_SESSION['access_token'] = $this->consumer->getToken();
				$_SESSION['access_token_secret'] = $this->consumer->getTokenSecret();
			}
			
			$this->consumer->setToken($_SESSION['access_token']);
			$this->consumer->setTokenSecret($_SESSION['access_token_secret']);
		}catch (Exception $e){
			echo $e->getMessage();
			exit;
		}
	}
	
	/**
	 * Consumer取得
	 */
	private function createConsumer($consumer_key, $consumer_secret){
		return new HTTP_OAuth_Consumer($consumer_key, $consumer_secret);
	}
	
	/**
	 * Consumer設定
	 */
	public function setConsumer($consumer_key, $consumer_secret){
		$this->consumer = $this->createConsumer($consumer_key, $consumer_secret);
	}

	/**
	 * RequestToken取得
	 */
	public function getRequestToken($callback){
		try{
			$this->consumer->getRequestToken(self::REQUEST_TOKEN_URL, $callback);
		}catch (Exception $e){
			echo $e->getMessage();
			exit;
		}
	}
	
	/**
	 * Twitpicへの投稿
	 */
	public function postImage($twitpic_key,$media,$status){
		require_once 'TwitPic/TwitPic.php';
		
		$twitpic = new TwitPic(
				$twitpic_key,
				$_SESSION['consumer_key'],
				$_SESSION['consumer_secret'],
				$_SESSION['access_token'],
				$_SESSION['access_token_secret']);
		
		try{
			$result = $twitpic->uploadAndPost(
				array('media'=>$media,'message'=>$status), 
				array('format'=>"xml")																																						 
			);
			
			$result = print_r($result, true);
		}catch (Exception $e){
			echo $e->getMessage();
			exit;
		}
		
	}

	/**
	 * リダイレクト
	 */
	public function redirect($url){
		header("Location: ".$url);
	}

	/**
	 * Getter
	 */
	public function __get($key){
			return $this->$key;
	}
	
	/**
	 * Setter
	 */
	public function __set($key, $value){
		$this->$key = $value;
	}
 
}
?>
