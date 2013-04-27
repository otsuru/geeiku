<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム 取込エラーチェック定義
//
//  ----変更内容----------------DATE--------Rev---------Auther----
//		新規作成				2011/01/28	Rev 1.0.0	koba
//  --------------------------------------------------------------
//
//=================================================================
// 入力チェック用設定ファイル
//
// 書式
//    フォームタイプ,
//    要素数（チェックボックス以外の場合、0）,
//    関連する設問(NULL_RELATION) || エラー時にFALSEになる正規表現(スラッシュなし)(REGEX) || エラー時にFALSEを返す自作関数(FUNCTION),
//    関連する設問の値(NULL_RELATION),
//    チェック種別
//
// フォームタイプ種別
//    TEXT
//    RADIO
//    CHECKBOX
//    SELECT
//
//  チェック種別
//    0
//    NULL				必須チェック
//    NULL_RELATION		関連する設問に、関連する設問の値が入力されたときのみ必須チェック
//    MAIL				メールアドレスチェック
//    NUM				数字のみチェック
//    HYPHENNUM			数字+ハイフンのみチェック
//    ALPHANUM			数字+アルファベットのみチェック
//    ALPHA				アルファベットのみチェック
//    FLOAT				浮動小数点数のみチェック
//    ZEN_KATAKANA		ひらがなのみチェック
//    HANKAKU_KATAKANA	半角カナのみチェック
//    REGEX				正規表現によるチェック
//    FUNCTION			自作関数によるチェック
//    BOOL				TRUE/FALSEによるチェック（FALSE時にエラー）
//----------------------------------------------------------------------------------------
$IO_Table["mst_agency[name]"] = 'TEXT, 0, , , NULL';
$IO_Table["mst_agency[code]"] = 'TEXT, 0, , , ALPHANUM';
$IO_Table["mst_agency[code_2]"] = 'TEXT, 0, , , ALPHANUM';
$IO_Table["mst_agency[code_3]"] = 'TEXT, 0, , , ALPHANUM';
//$IO_Table["mst_manage[hcsf_code]"] = 'TEXT, 0, , , NULL';
//$IO_Table["mst_agency[owner_code]"] = 'TEXT, 0, , , ALPHANUM';
$IO_Table["mst_manage[name]"] = 'TEXT, 0, , , NULL';
$IO_Table["mst_manage[code]"] = 'TEXT, 0, , , ALPHANUM';
$IO_Table["mst_manage[plan_name]"] = 'TEXT, 0, , , NULL';
$IO_Table["mst_manage[plan_code]"] = 'TEXT, 0, , , NULL';
$IO_Table["mst_manage[hnetsf_flg]"] = 'TEXT, 0, , , NUM';
$IO_Table["mst_manage[sales_code]"] = 'TEXT, 0, ^[123]{1}$, , NULL, REGEX';
$IO_Table["mst_manage[delivery_code]"] = 'TEXT, 0, ^[12]{1}$, , NULL, REGEX';
$IO_Table["mst_manage[payment_code]"] = 'TEXT, 0, ^[01234]{1}$, , NULL, REGEX';
$IO_Table["mst_manage[agency_open_code]"] = 'TEXT, 0, ^[01]{1}$, , NULL, REGEX';
$IO_Table["mst_manage[mail_send_code]"] = 'TEXT, 0, ^[01]{1}$, , NULL, REGEX';
$IO_Table["mst_manage[campaign_code]"] = 'TEXT, 0, , , ALPHANUM';
//$IO_Table["mst_agency[ts_url]"] = 'TEXT, 0, , , NULL';
//$IO_Table["mst_agency[site_url]"] = 'TEXT, 0, , , NULL';
//$IO_Table["mst_agency[privacy_url]"] = 'TEXT, 0, , , NULL';


/* ローカルライブラリ
----------------------------------------------------------------*/


?>