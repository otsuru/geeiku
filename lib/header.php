<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]ページ
//                              共通ヘッダ
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/07/08 Rev 1.0.0  otsuru
//  --------------------------------------------------------------
//
//=================================================================

/* 設定ファイル/ライブラリファイルインクルード
------------------------------------------------------*/
require_once(CNF_LIB_PATH.'lib.php');
//require_once(CNF_LIB_PATH.'lib_toppa.php');
require_once(CNF_LIB_PATH.'entry_check.php');
//require_once(CNF_CLASS_PATH.'db.class.php');
require_once(CNF_CLASS_PATH.'Request.class.php');
//require_once(CNF_ETC_PATH.'const_message.php');

set_include_path(get_include_path() . PATH_SEPARATOR . CNF_LIB_PATH.'pear/');

/* リクエストオブジェクト生成
------------------------------------------------------*/
$objForm = new Request();
$f =& $objForm;	// $objFormのエイリアス


/* クラス自動読み込み設定
------------------------------------------------------*/
function __autoload($classname){
	$load_filename = CNF_CLASS_PATH . $classname . ".class.php";
	@include_once $load_filename;
	if(!class_exists($classname)){
		die($classname." class not exists.");
	}
}

/* DB接続
------------------------------------------------------*/

$dbcon = new db();
$dbcon->connect($cnf_hDB{"user"}, $cnf_hDB{"pass"}, $cnf_hDB{"dbname"}, $cnf_hDB{"hostname"});
$dbcon->setLog($cnf_sql_log, $cnf_sql_errlog);

?>