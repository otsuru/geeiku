<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム エラーチェック定義
//
//  ----変更内容----------------DATE--------Rev---------Auther----
//		新規作成				2011/01/25	Rev 1.0.0	koba
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
$IO_Table["name"] = 'TEXT, 0, , , NULL';

$IO_Table["chara"] = 'SELECT, 0, , , NULL';

foreach($cst_question as $key => $value){
	if($f->params["question_".$key] == "1"){
		$flag = "1";
	}
}
if(!isset($flag)){
	$IO_Table["question"] = 'CHECKBOX, 0, , , NULL';
}

$IO_Table["exp"] = 'RADIO, 0, , , NULL';
$IO_Table["prac"] = 'RADIO, 0, , , NULL';
/* エラーグループ定義
----------------------------------------------------------------*/
$hErrType = array(
	'name' => 'base',
	'chara' => 'base',
	'question' => 'base',
	'exp' => 'base',
	'prac' => 'base',
);

/* エラーメッセージ定義
----------------------------------------------------------------*/
define('Err_name', '※エントリーネームを入力してください。');
define('Err_chara', '※使用キャラクターを選択してください。');
define('Err_exp', '※ミカド初心者対戦会に参加経験があるか選択してください。');
define('Err_prac', '※練習台を使用したいか否かを選択してください。');
define('Err_question', '※「ギルティギアシリーズに関しての質問」を選択してください');

/* ローカルライブラリ
----------------------------------------------------------------*/
// 姓名（ローマ字）大文字チェック
function _chkNameEngAlpha(){
	global $f;
	
	$name = $f->name_eng_1.$f->name_eng_2;
	if ($name != ""){
		if (!preg_match("/^[A-Za-z]+$/", $name)){
			return false;
		}
	}
	
	return true;
}

// 生年月日形式チェック
function _chkBirth(){
	global $f;
	
	// 形式チェック
	if (fChkDate(sprintf("%04d-%02d-%02d", $f->birth_y, $f->birth_m, $f->birth_d)) == 0){
		return false;
	}
	
	$age = (date('Ymd') - sprintf("%04d%02d%02d", $f->birth_y, $f->birth_m, $f->birth_d)) / 10000;
	
	// 年齢制限（0歳 - 120歳）チェック
	if (!(0 < $age && $age < 120)){
		return false;
	}
	
	return true;
}

// 未成年チェック
function _chkBirthAge(){
	global $f;
	
	if (_chkBirth() == true){
		$age = (date('Ymd') - sprintf("%04d%02d%02d", $f->birth_y, $f->birth_m, $f->birth_d)) / 10000;
		
		if ($age < 20){
			return false;
		}
	}
	
	return true;
}

// カード有効期限チェック
function _chkCardYM(){
	global $f;
	
	if (!preg_match("/^[0-9]{2}$/", $f->card_y)){
		return false;
	}
	if (!preg_match("/^[01]?[0-9]$/", $f->card_m)){
		return false;
	}
	if (!(1 <= $f->card_m && $f->card_m <= 12)){
		return false;
	}
	if (date('Ym') > sprintf("20%02d%02d", $f->card_y, $f->card_m)){
		return false;
	}
	
	return true;
}

// メールアドレス一致チェック
function _chkMailDiff(){
	global $f;
	
	if ($f->email != "" || $f->email_conf != ""){
		if ($f->email != $f->email_conf){
			return false;
		}
	}
	
	return true;
}

// 端末シリアルNoチェック
function _chkTerminalSerial(){
	global $f;
	
	$sn = "";
	for ($i=1; $i<=20; $i++){
		$tmp = "terminal_makeno_".$i;
		$sn .= $f->$tmp;
	}
	
	return (($sn == "") ? false : true);
}

// 端末MACアドレスチェック
function _chkTerminalMac(){
	global $f;
	
	$mac = "";
	for ($i=1; $i<=12; $i++){
		$tmp = "terminal_macadd_".$i;
		$mac .= $f->$tmp;
	}
	
	return ((preg_match("/^[0-9a-fA-F]{12}$/", $mac)) ? true : false);
}

?>
