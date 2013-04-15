<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム
//                              管理画面用ヘッダ
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/01/28 Rev 1.0.0  koba
//  --------------------------------------------------------------
//
//=================================================================

/* 設定ファイル/ライブラリファイルインクルード
------------------------------------------------------*/
require_once(CNF_LIB_PATH.'lib.php');
require_once(CNF_LIB_PATH.'entry_check.php');
require_once(CNF_CLASS_PATH.'db.class.php');
require_once(CNF_CLASS_PATH.'Request.class.php');
require_once(CNF_ETC_PATH.'const_message_admin.php');
require_once(CNF_CLASS_PATH.'AuthAdmin.class.php');


/* リクエストオブジェクト生成
------------------------------------------------------*/
$objForm = new Request();
$f =& $objForm;	// $objFormのエイリアス


/* DB接続
------------------------------------------------------*/
$dbcon = new db();
$dbcon->connect($cnf_hDB{"user"}, $cnf_hDB{"pass"}, $cnf_hDB{"dbname"}, $cnf_hDB{"hostname"});
$dbcon->setLog($cnf_sql_log, $cnf_sql_errlog);


/* 認証
------------------------------------------------------*/
$auth = new AuthAdmin($dbcon, $objForm);

?>