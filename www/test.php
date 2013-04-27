<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]初心者対戦会告知ページ
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

$strSQL = "select * from tbl_beginner order by id asc";
$ahBeginner = $dbcon->fetch_all($dbcon->query($strSQL));

include CNF_HTML_PATH.'event_beginner_test.html';
?>