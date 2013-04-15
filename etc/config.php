<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム 共通設定ファイル
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/01/23  Rev 1.0.0 koba
//  --------------------------------------------------------------
//
//=================================================================

/* 各種パス設定 Basic
----------------------------------------------------------------*/
// 開発環境
if ($_SERVER['SERVER_NAME'] == 'sou-opinion.sakura.ne.jp') {
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set("display_errors", 'On');
	
	define('CNF_SV_TYPE', 0);	// 0: 開発環境 / 1: 本番
	define('CNF_DOMAIN', 'sou-opinion.sakura.ne.jp');
	define('CNF_ROOT', '/home/sou-opinion/');
	define('CNF_DOCROOT_PATH', CNF_ROOT.'www/');
	define('CNF_ROOT_URL', 'http://'.CNF_DOMAIN.'/');
	define('CNF_ROOT_URL_SSL', 'http://'.CNF_DOMAIN.'/');
}

// 本番
elseif ($_SERVER['SERVER_NAME'] == 'www.entry-t.tp1.jp' || $_SERVER['SERVER_NAME'] == 'entry-t.tp1.jp'){
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set("display_errors", 'On');

	define('CNF_SV_TYPE', 1);	// 0: テスト /1: 本番 /2: 本番テスト
	define('CNF_DOMAIN', 'www.entry-t.tp1.jp');
	define('CNF_ROOT', '/home/entryt/');
	define('CNF_DOCROOT_PATH', CNF_ROOT.'html/');
	define('CNF_ROOT_URL', 'http://'.CNF_DOMAIN.'/');
	define('CNF_ROOT_URL_SSL', 'https://'.CNF_DOMAIN.'/');
}

else {
	print "Setting Error.";
	exit();
}

// 設定ファイル入りディレクトリ
DEFINE('CNF_ETC_PATH', CNF_ROOT.'etc/');

// チェックファイルディレクトリ
DEFINE('CNF_CHECK_PATH', CNF_ETC_PATH.'check/');

// ライブラリディレクトリ
DEFINE('CNF_LIB_PATH', CNF_ROOT.'lib/');

// クラスディレクトリ
DEFINE('CNF_CLASS_PATH', CNF_LIB_PATH.'class/');

// その他ディレクトリ
DEFINE('CNF_VAR_PATH', CNF_ROOT.'var/');

// テンプレートディレクトリ
DEFINE('CNF_HTML_PATH',CNF_VAR_PATH.'temp_html/');
DEFINE('CNF_MAIL_PATH',CNF_VAR_PATH.'temp_mail/');


/* 各種パス設定 Extend
----------------------------------------------------------------*/
// 申込データ格納ディレクトリ
DEFINE('CNF_ORDER_DATA_PATH', CNF_VAR_PATH.'data/');
DEFINE('CNF_FORM_PATH', CNF_ETC_PATH.'form/');


/* SQLログ場所
----------------------------------------------------------------*/
// エラーログ
$cnf_sql_errlog = CNF_VAR_PATH.'sql_log/sql_err.log';
// 操作ログ
$cnf_sql_log = CNF_VAR_PATH.'sql_log/sql.log';


/* クッキー設定
----------------------------------------------------------------*/
// 管理画面
DEFINE('CNF_LOGIN_ID', 'ADM_LOGIN_ID');
DEFINE('CNF_LOGIN_PASS', 'ADM_LOGIN_PASS');
DEFINE('CNF_LOGIN_AUTH', 'ADM_LOGIN_AUTH');
DEFINE('CNF_LOGIN_EXPIRE', 0);


/*  DB設定
----------------------------------------------------------------*/

$cnf_hDB = array();
// ミラー
if (CNF_SV_TYPE == 0){
	$cnf_hDB{"dbname"} = 'sou-opinion_ac_db';
	$cnf_hDB{"hostname"} = 'mysql429.db.sakura.ne.jp';
	$cnf_hDB{"user"} = 'sou-opinion';
	$cnf_hDB{"pass"} = 'qFpSqwNJni6A';
}
// 本番
elseif (CNF_SV_TYPE == 1){
	$cnf_hDB{"dbname"} = 'entryt_aesdb';
	$cnf_hDB{"hostname"} = 'localhost';
	$cnf_hDB{"user"} = 'entryt_aesdb';
	$cnf_hDB{"pass"} = 'LZCtT&c%INA$';
}

/*  その他
----------------------------------------------------------------*/

define('CNF_NOW_DATETIME', date('Y-m-d H:i:s'));
define('CNF_NOW_DATE', date('Ymd'));
//define('CNF_NOW_DATE_TXT', date('Y/m/d'));
//define('CNF_NOW_DATETIME14', date('YmdHis'));


/*  アップロード設定
----------------------------------------------------------------*/


/*  ユーザーID設定
----------------------------------------------------------------*/
// 固定加算値
define('CNF_USER_ID_ADD', 53816021);


/* タグ設定
----------------------------------------------------------------*/
// 許可するタグ
$aAllowTag = array(
	"a",
	"b",
	"i",
	"font",
);

?>