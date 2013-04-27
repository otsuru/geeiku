<?php
//=================================================================
//
//   認証クラス - 管理画面
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2010/12/01 Rev 1.0.0  Koba
//  --------------------------------------------------------------
//   ログ保存機能追加　　　　　　 2010/12/13 Rev 1.0.1  Koba
//  --------------------------------------------------------------
//
//=================================================================

class AuthAdmin {
	// DB接続クラス
	var $dbcon;

	// Requestクラス
	var $objForm;

	// 認証情報
	var $strLoginId = "";
	var $strLoginPass = "";
	var $strLoginCode = "";
	
	// ユーザー情報
	var $hUser;

	/*---------------------------------------------------------------
	 * コンストラクタ
	 * クッキーからログイン情報、認証フラグを取得し、
	 * 未ログイン状態だった場合にログインフォームを表示
	----------------------------------------------------------------*/
	function __construct(&$dbcon, &$objForm)
	{
		$this->dbcon = $dbcon;
		$this->objForm = $objForm;

		$this->strLoginId = isset($_COOKIE[CNF_ADMIN_LOGIN_ID]) ? $_COOKIE[CNF_ADMIN_LOGIN_ID] : "";
		$this->strLoginPass = isset($_COOKIE[CNF_ADMIN_LOGIN_PASS]) ? $this->deCrypt($_COOKIE[CNF_ADMIN_LOGIN_PASS]) : "";

		// ログインフォームから認証実行時
		if ($this->objForm->get("auth_flg") == "1"){
			// エラーチェック
			$errmsg = $this->chkAuthForm($objForm);

			// えらーあり
			if ($errmsg != ""){
				$this->viewLoginForm($errmsg);
			}
			// えらーなし
			else {
				// 認証失敗時
				if (!$this->_execAuth($this->objForm->get("login_id"), $this->objForm->get("login_pass"))){
					$this->viewLoginForm(AUTH_03);
				}
				// 認証成功時
				else {
					// $this->setAccessLog(2);
					
					$this->strLoginId = $this->objForm->get("login_id");
					$this->strLoginPass = $this->objForm->get("login_pass");
					
					// リクエストされた画面へ遷移
					header("Location:".$_SERVER["REQUEST_URI"]);
				}
			}
		}
		// ログインフォーム以外、通常アクセス時
		else {
			// クッキーなし時
			if (!isset($_COOKIE[CNF_ADMIN_LOGIN_ID]) || !isset($_COOKIE[CNF_ADMIN_LOGIN_PASS])){
				$this->viewLoginForm();
			}
			// クッキーあり時
			else {
				// 不正なアクセス時
				if (!$this->_execAuth($this->strLoginId, $this->strLoginPass)){
					$this->setAccessLog(0);
					
					// クッキー削除
					$this->delCookie();

					$this->viewLoginForm(AUTH_04);
				}
			}
		}

		// 認証成功時、クッキーに保存
		$this->setCookie();
	}

	/*---------------------------------------------------------------
	 * ログインフォームのエラーチェック
	----------------------------------------------------------------*/
	function chkAuthForm()
	{
		$errmsg = "";

		// エラーチェック
		if ($this->objForm->get("login_id") == ""){
			$errmsg .= AUTH_01."\n";
		}
		if ($this->objForm->get("login_pass") == ""){
			$errmsg .= AUTH_02."\n";
		}

		return $errmsg;
	}

	/*---------------------------------------------------------------
	 * 認証実行
	 * 戻り値：FALSE -> 認証失敗
	 * 		   TRUE  -> 認証成功
	----------------------------------------------------------------*/
	function _execAuth($id, $pass)
	{
		$strSQL  = "select * from tbl_admin_user where ";
		$strSQL .= "account = '".$id."' ";
		$strSQL .= "and passwd = '".$pass."'";
		
		// 取込画面時
		if (preg_match("/\/import\//", $_SERVER["REQUEST_URI"])){
			$strSQL .= "and import_flg = 1";
		}
		// ダウンロード画面時
		elseif (preg_match("/\/download\//", $_SERVER["REQUEST_URI"])){
			$strSQL .= "and download_flg = 1";
		}
		// その他
		else {
			return FALSE;
		}

		$strSQL .= ";";

		$ahRet = $this->dbcon->fetch_all($this->dbcon->query($strSQL));

		if ($ahRet[0]["id"] == ""){
			return FALSE;
		} else {
			$this->hUser = $ahRet[0];
			return TRUE;
		}
	}

	/*---------------------------------------------------------------
	 * ログイン画面表示
	----------------------------------------------------------------*/
	function viewLoginForm($strMsg = "")
	{
		$objForm = $this->objForm;

		include CNF_HTML_PATH.'admin/login.html';

		exit;
	}

	/*---------------------------------------------------------------
	 * ログイン情報をクッキーにセット
	----------------------------------------------------------------*/
	function setCookie()
	{
		setcookie(CNF_ADMIN_LOGIN_ID, $this->strLoginId, CNF_LOGIN_EXPIRE, '/');
		setcookie(CNF_ADMIN_LOGIN_PASS, $this->enCrypt($this->strLoginPass), CNF_LOGIN_EXPIRE, '/');
		//setcookie(CNF_ADMIN_LOGIN_AUTH, $this->nAuth, CNF_LOGIN_EXPIRE, '/');
	}

	/*---------------------------------------------------------------
	 * ログイン情報をクッキーからクリア
	----------------------------------------------------------------*/
	function delCookie()
	{
		setcookie(CNF_ADMIN_LOGIN_ID, "", -1, '/');
		setcookie(CNF_ADMIN_LOGIN_PASS, "", -1, '/');
		//setcookie(CNF_ADMIN_LOGIN_AUTH, "", -1, '/');
	}

	/*---------------------------------------------------------------
	 * 文字列を暗号化
	----------------------------------------------------------------*/
	function enCrypt(&$str)
	{
		return str_rot13(base64_encode($str));
	}

	/*---------------------------------------------------------------
	 * 文字列を復号化
	----------------------------------------------------------------*/
	function deCrypt(&$str)
	{
		return base64_decode(str_rot13($str));
	}

	/*---------------------------------------------------------------
	 * アクセス履歴保存
	 * $type:	0 -> ログインフォーム初回表示
	 * 			1 -> ログインエラー
	 * 			2 -> ログイン済み
	----------------------------------------------------------------*/
	function setAccessLog($type)
	{
		$strSQL  = "insert into tbl_log_admin (";
		$strSQL .= "account, url, mode, auth_flg, http_referer, remote_addr, user_agent, input_date";
		$strSQL .= ") values (";
		$strSQL .= "'".(($this->objForm->get("login_id") == "") ? $this->strLoginId : $this->objForm->get("login_id"))."'";
		$strSQL .= ", '".$_SERVER["REQUEST_URI"]."'";
		$strSQL .= ", '".$this->objForm->get("mode")."'";
		$strSQL .= ", ".$type;
		$strSQL .= ", '".$_SERVER["HTTP_REFERER"]."'";
		$strSQL .= ", '".$_SERVER["REMOTE_ADDR"]."'";
		$strSQL .= ", '".$_SERVER["HTTP_USER_AGENT"]."'";
		$strSQL .= ", '".CNF_NOW_DATETIME."'";
		$strSQL .= ");";
		
		$this->dbcon->query($strSQL);
	}

}

?>