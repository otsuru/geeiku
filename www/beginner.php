<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]エントリーページ（初心者講習会
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

include_once CNF_FORM_PATH.'beginner.php';
$form = new WidgetForm($widget, $f);
$form->makeTag();


$strSQL = "select * from tbl_beginner order by id asc";
$ahBeginner = $dbcon->fetch_all($dbcon->query($strSQL));

if ($f->mode == ""  || $f->mode == "back" || $f->back != ""){
	$mode = "form";
}elseif($f->mode == "form"){
	// フォームエラーチェック
	include CNF_CHECK_PATH.'chk_beginner.php';
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
	}

	// エラーあり
	if ($intErrCnt > 0){
		$hErrMsg["base"] = "入力にエラーがあります";
		$mode = "form";
	}else{
		//確認ページ
		$mode = "conf";
		$form->freeze();
	}
}elseif($f->mode == "conf"){

	$strSQL = "";
	$strSQL = "insert into tbl_beginner ";
	$strSQL .= "(";		$strSQL_value = "values(";
	
	$strSQL .= "name";			$strSQL_value .= "'".$f->name."'";
	$strSQL .= ",chara";		$strSQL_value .= ",'".$cst_form_chara[$f->chara]."'";
	$strSQL .= ",exp";		$strSQL_value .= ",".$f->exp;
	$strSQL .= ",prac";		$strSQL_value .= ",".$f->prac;
	
	$question = "";
	foreach($cst_question as $key => $value){
		if($f->params["question_".$key] == "1"){
			$question .= $key."/";
		}
	}
	$question = preg_replace('/\/$/',"",$question);
	$strSQL .= ",question";		$strSQL_value .= ",'".$question."'";
	$strSQL .= ",message";		$strSQL_value .= ",'".$f->message."'";
	$strSQL .= ",message2";		$strSQL_value .= ",'".$f->message2."'";
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

include CNF_HTML_PATH.'beginner.html';
?>