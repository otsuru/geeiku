<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム 認証クラス（ショップ向け）
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/01/23 Rev 1.0.0  Koba
//  --------------------------------------------------------------
//
//=================================================================

class Auth {
	// Requestクラス
	var $objForm;

	// 認証情報
	var $strLoginNo = "";
	var $strLoginCompany = "";
	var $strLoginName = "";

	/*---------------------------------------------------------------
	 * コンストラクタ
	 * POST情報からログイン情報、認証フラグを取得し、
	 * 未ログイン状態だった場合にログインフォームを表示
	----------------------------------------------------------------*/
	function __construct(&$objForm)
	{
		$this->objForm = $objForm;

		$this->strLoginNo = $objForm->get("manage_no");
		$this->strLoginName = $objForm->get("manage_name");
		$this->strLoginCompany = $objForm->get("manage_company");

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
				// リクエストされた画面へ遷移
				//$url = $_SERVER["REQUEST_URI"]
				//	."&manage_no=".urlencode($objForm->get("manage_no"))
				//	."&manage_name=".urlencode($objForm->get("manage_name"))
				//	."&manage_company=".urlencode($objForm->get("manage_company"));
				$url = "/smp/?no=".$this->objForm->get("no")
					."&manage_no=".urlencode($objForm->get("manage_no"))
					."&manage_name=".urlencode($objForm->get("manage_name"))
					."&manage_company=".urlencode($objForm->get("manage_company"));
				
				$_SESSION["manage_no"] = $objForm->get("manage_no");
				$_SESSION["manage_name"] = $objForm->get("manage_name");
				$_SESSION["manage_company"] = $objForm->get("manage_company");
				
				header("Location:".$url);
			}
		}
		// ログインフォーム以外、通常アクセス時
		else {
			// GETアクセス＆リファラなし時、ログインフォーム表示
			if ($_SERVER["REQUEST_METHOD"] == "GET" && $_SERVER["HTTP_REFERER"] == ""){
				$this->viewLoginForm();
			}
			// URLにログアウト指定があった場合、ログインフォーム表示
			elseif ($this->objForm->get("logout") != ""){
				$_SESSION = array();
				$this->viewLoginForm();
			}
/*
			// ログイン情報なし時
			if ($this->strLoginNo == "" || $this->strLoginName == "" || $this->strLoginCompany == ""){
				$this->viewLoginForm();
			}
*/
		}
	}

	/*---------------------------------------------------------------
	 * ログインフォームのエラーチェック
	----------------------------------------------------------------*/
	function chkAuthForm()
	{
		$errmsg = "";

/*
		// エラーチェック
		if ($this->objForm->get("manage_no") == ""){
			$errmsg .= AUTH_01."\n";
		}
		if ($this->objForm->get("manage_name") == ""){
			$errmsg .= AUTH_02."\n";
		}
		if ($this->objForm->get("manage_company") == ""){
			$errmsg .= AUTH_03."\n";
		}
*/

		return $errmsg;
	}

	/*---------------------------------------------------------------
	 * ログイン画面表示
	----------------------------------------------------------------*/
	function viewLoginForm($strMsg = "")
	{
		include CNF_ETC_PATH.'const_status.php';
		global $ahManage;
		global $ahAgency;

		$objForm = $this->objForm;

		include CNF_HTML_PATH.'smp/login.html';

		exit;
	}

}

?>