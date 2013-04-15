<?php
//=================================================================
//
//  光通信(toppa!)用共通関数
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/08/03 Rev 1.0.0  otsuru
//  --------------------------------------------------------------
//
//=================================================================
/* 文字コード変更
------------------------------------------------------*/
function in($str, $to_encode = "UTF-8", $from_encode = "SJIS-win"){
	mb_internal_encoding($from_encode);
	mb_regex_encoding($from_encode);
	
	$ret = null;
	
	if (is_array($str)){
		foreach ($str as $key => $value){
			$ret[$key] = in($value, $to_encode, $from_encode);
		}
	} else {
		$ret = mb_convert_encoding($str, $to_encode, $from_encode);
	}
	return $ret;
}

function out($str,$to_encode_out = "SJIS-win", $from_encode_out = "UTF-8"){
	//header_mで作成
	global $carrier;
	
	//PC、スマホの場合
	if($carrier == "1"){
		return $str;
		exit;
	}
	mb_internal_encoding($from_encode_out);
	mb_regex_encoding($from_encode_out);	
	$ret = null;

	if (is_array($str)){
		foreach ($str as $key => $value){
			$ret[$key] = out($value, $to_encode_out, $from_encode_out);
		}
	} else {
		//携帯
		if($carrier == "2"){
			$str = mb_eregi_replace("　+","　",$str);
			$str = mb_convert_kana($str,"ka");
		} 
		$ret = mb_convert_encoding($str, $to_encode_out, $from_encode_out);
	}
	return $ret;
}

//携帯（一部で使用）
function out_m($str,$to_encode_out = "SJIS-win", $from_encode_out = "UTF-8"){
	//header_mで作成
	global $carrier;
	
	mb_internal_encoding($from_encode_out);
	mb_regex_encoding($from_encode_out);	
	$ret = null;

	if (is_array($str)){
		foreach ($str as $key => $value){
			$ret[$key] = out_m($value, $to_encode_out, $from_encode_out);
		}
	} else {
		$ret = mb_convert_encoding($str, $to_encode_out, $from_encode_out);
	}
	return $ret;
}

// PC/携帯/スマホ/タブレット識別
//	1: PC
//	2: 携帯
//	3: スマホ
function carrierAnalyzeAll(){
	$ret = 1;
	
	// 携帯
	if ( preg_match( "/(DoCoMo|UP\.Browser|KDDI\-|J-PHONE|Vodafone|SoftBank|WILLCOM|MOT)/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$ret = 2;
	}
	// スマホ
	elseif ( preg_match( "/(iPhone|iPod|iPad|Android|BlackBerry)/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$ret = 3;
	}
	
	return $ret;
}

/* Crypt関連
------------------------------------------------------*/
// 暗号化
/*
function encryptStrHex($str)
{
	$str = base64_encode($str);
	$encrypted = "";
	for ($i=0; $i<strlen($str); $i++){
		$chr = ord(substr($str, $i, 1));
		$encrypted .= sprintf("%02s", dechex($chr));
	}
	
	return $encrypted;
}

// 複合化
function decryptStrHex($str)
{
	$decrypted = "";
	for ($i=0; $i<strlen($str); $i=$i+2){
		$decrypted .= chr(hexdec(substr($str, $i, 2)));
	}
	$decrypted = base64_decode($decrypted);
	
	return $decrypted;
}
*/
?>