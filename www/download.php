<?php
//=================================================================
//
//   [GGXXAC The Last Batte運営ページ]ダウンロードページ
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


// 一覧表示
if ($f->mode == ""){
    include CNF_HTML_PATH.'download.html';
}

// ダウンロード
if ($f->mode == "rand"){
	$random = rand(1,83);
    include CNF_HTML_PATH.'download.html';
	exit;
}
// ダウンロード
if ($f->mode == "single"){
	
	/* データ取得
	------------------------------------------------------*/
	// 設定済みの代理店データ・販売管理データを取得
	$strSQL = "select * from tbl_single order by id asc";
	$ahSingle = $dbcon->fetch_all($dbcon->query($strSQL));
	shuffle($ahSingle);
	/* データ作成
	------------------------------------------------------*/
	// ヘッダ行
	$output_head	= "";
	// データ行
	$output_body = "";
	if ($ahSingle[0]["id"] != ""){
		for ($i=0; $i<count($ahSingle); $i++){
			//$output_body .= $ahSingle[$i]["id"];
			preg_match_all('/\((.*?)\)/',$ahSingle[$i][chara],$matches);
			$output_body .= $ahSingle[$i]["name"]." ".$matches[0][0]."\r\n";
			//$output_body .= "\r\n";
		}
	}
	
	// 出力
	header("Content-type: application/octet-stream;");
	header("Content-disposition: attachment; filename=single_".date('Ymd').".csv;");
	print mb_convert_encoding($output_head.$output_body,"SJIS-win","UTF-8");
}
// ダウンロード
if ($f->mode == "single_r"){
	
	/* データ取得
	------------------------------------------------------*/
	// 設定済みの代理店データ・販売管理データを取得
	$strSQL = "select * from tbl_single_r order by id asc";
	$ahSingle_r = $dbcon->fetch_all($dbcon->query($strSQL));

	shuffle($ahSingle_r);
	/* データ作成
	------------------------------------------------------*/
	// ヘッダ行
	$output_head	= "";
	// データ行
	$output_body = "";
	if ($ahSingle_r[0]["id"] != ""){
		for ($i=0; $i<count($ahSingle_r); $i++){
			//$output_body .= $ahSingle_r[$i]["id"];
			preg_match_all('/\((.*?)\)/',$ahSingle_r[$i][chara],$matches);
			$output_body .= $ahSingle_r[$i]["name"]."  ".$matches[0][0]."\r\n";
			//$output_body .= "\r\n";
		}
	}
	
	// 出力
	header("Content-type: application/octet-stream;");
	header("Content-disposition: attachment; filename=single_r_".date('Ymd').".csv;");
	print mb_convert_encoding($output_head.$output_body,"SJIS-win","UTF-8");
}

// ダウンロード
if ($f->mode == "team"){
	
	/* データ取得
	------------------------------------------------------*/
	// 設定済みの代理店データ・販売管理データを取得
	$strSQL = "select * from tbl_team order by id asc";
	$ahTeam = $dbcon->fetch_all($dbcon->query($strSQL));

	shuffle($ahTeam);
	/* データ作成
	------------------------------------------------------*/
	// ヘッダ行
	$output_head	= "";
	// データ行
	$output_body = "";
	if ($ahTeam[0]["id"] != ""){
		for ($i=0; $i<count($ahTeam); $i++){
			preg_match_all('/\((.*?)\)/',$ahTeam[$i][chara_1],$matches);
			preg_match_all('/\((.*?)\)/',$ahTeam[$i][chara_2],$matches2);
			preg_match_all('/\((.*?)\)/',$ahTeam[$i][chara_3],$matches3);
			$output_body .= $ahTeam[$i]["team_name"].",";
			$output_body .= $ahTeam[$i]["name_1"].$matches[0][0].",";
			$output_body .= $ahTeam[$i]["name_2"].$matches2[0][0].",";
			$output_body .= $ahTeam[$i]["name_3"].$matches3[0][0]."\r\n";
		}
	}
	
	// 出力
	header("Content-type: application/octet-stream;");
	header("Content-disposition: attachment; filename=team_".date('Ymd').".csv;");
	print mb_convert_encoding($output_head.$output_body,"SJIS-win","UTF-8");
}
?>