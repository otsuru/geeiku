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

<<<<<<< HEAD
$strSQL = "select * from tbl_single ORDER BY id desc ";
=======
$strSQL = "select * from tbl_single ORDER BY id desc ";
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
$ahCount_s = $dbcon->fetch_all($dbcon->query($strSQL));

//var_dump($ahCount_s);

<<<<<<< HEAD
$strSQL = "select * from tbl_single_r ORDER BY id desc ";
$ahCount_r = $dbcon->fetch_all($dbcon->query($strSQL));


$strSQL = "select * from tbl_team ORDER BY id desc ";
=======
$strSQL = "select * from tbl_single_r ORDER BY id desc ";
$ahCount_r = $dbcon->fetch_all($dbcon->query($strSQL));


$strSQL = "select * from tbl_team ORDER BY id desc ";
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
$ahCount_t = $dbcon->fetch_all($dbcon->query($strSQL));

include CNF_HTML_PATH.'entry_conf.html';
?>