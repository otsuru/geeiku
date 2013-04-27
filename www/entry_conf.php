<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]エントリー状況確認ページ
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2012/06/24 Rev 1.0.0 otsuru
//  --------------------------------------------------------------
//
//=================================================================


/* 設定ファイル等インクルード
----------------------------------------------------------------*/
include '../etc/config.php';
include CNF_LIB_PATH.'header.php';

$strSQL = "select * from tbl_single ORDER BY id desc ";
$ahCount_s = $dbcon->fetch_all($dbcon->query($strSQL));

$strSQL = "select * from tbl_single_r ORDER BY id desc ";
$ahCount_r = $dbcon->fetch_all($dbcon->query($strSQL));


$strSQL = "select * from tbl_team ORDER BY id desc ";
$ahCount_t = $dbcon->fetch_all($dbcon->query($strSQL));

include CNF_HTML_PATH.'entry_conf.html';
?>