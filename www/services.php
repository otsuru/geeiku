<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]エントリーページ（斡旋
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
include_once CNF_FORM_PATH.'const.php';
<<<<<<< HEAD

include_once CNF_FORM_PATH.'single.php';
$form = new WidgetForm($widget, $f);
=======

include_once CNF_FORM_PATH.'single.php';
$form = new WidgetForm($widget, $f);
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
$form->makeTag();


if ($f->mode == ""  || $f->mode == "back" || $f->back != ""){
	$mode = "form";
}elseif($f->mode == "form"){
	// フォームエラーチェック
	include CNF_CHECK_PATH.'chk_single.php';
	$hErr = errCheck($objForm, $IO_Table);
	$intErrCnt = 0;
	$hErrMsg = array();
	$hForm["ErrMsg"] = "";
	foreach ($hErr as $key => $value){
		if (!preg_match("/^[0-9]+$/", $value)){
			$intErrCnt++;
			if ($value == -1){
				$strErrMsg .= constant("Err_".$key)."\r\n";
			} else {
				$strErrMsg .= constant("Err_".$key."_".$value)."\r\n";
			}
		}
<<<<<<< HEAD
	}

	// エラーあり
	if ($intErrCnt > 0){
		$hErrMsg["base"] = "入力にエラーがあります";
		$mode = "form";
	}else{
		//確認ページ
		$mode = "conf";
=======
	}

	// エラーあり
	if ($intErrCnt > 0){
		$hErrMsg["base"] = "入力にエラーがあります";
		$mode = "form";
	}else{
		//確認ページ
		$mode = "conf";
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
		$form->freeze();
	}
}elseif($f->mode == "conf"){

	$strSQL = "";
	$strSQL = "insert into tbl_services ";
	$strSQL .= "(";		$strSQL_value = "values(";
	
	$strSQL .= "name";			$strSQL_value .= "'".$f->name."'";
	$strSQL .= ",chara";		$strSQL_value .= ",'".$cst_form_chara[$f->chara]."'";
	$strSQL .= ",message";		$strSQL_value .= ",'".$f->message."'";
	$strSQL .= ", remote_addr";		$strSQL_value .= ", '".$_SERVER["REMOTE_ADDR"]."'";
	$strSQL .= ", user_agent";		$strSQL_value .= ", '".$_SERVER["HTTP_USER_AGENT"]."'";
	$strSQL .= ",input_date";		$strSQL_value .= ",".CNF_NOW_DATE;
	$strSQL .= ",input_datetime";	$strSQL_value .= ",'".CNF_NOW_DATETIME."'";
	$strSQL .= ")";					$strSQL_value .= ");";
	
	if(!$dbcon->query($strSQL.$strSQL_value)){
		//print $strSQL.$strSQL_value;
		print "001エラーが発生しました。担当者にご連絡下さい";
		exit;
	}else{
		header("Location: ./end.php");
		exit;
	}
}

include CNF_HTML_PATH.'services.html';
?>