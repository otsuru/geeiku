<?php
//
// 汎用ライブラリ
// CreateDate : 2005/03/30
// ModifyDate : 2008/06/24
// File       : lib.php
// By.koba+sudo
//


// HTML改行タグ（<br>）を改行コード(\n)に変換
function htmlnewline_unescape($str)
{
	$str = mb_eregi_replace("<br?( /)>?(\x0D\x0A|\x0D|\x0A)", "\x0A", $str);
	$str = mb_eregi_replace("&lt;br?( /)&gt?(\x0D\x0A|\x0D|\x0A);", "\x0A", $str);
	$str = mb_eregi_replace("<br>", "\x0A", $str);
	
	return $str;
}

// 引数内のURLをチェックし、アンカーをはったものに置換
function url_link_replace($str)
{
	$regex = 's?https?:\/\/[-_\.!~*\'\(\)a-zA-Z0-9;\/\?:@&=\+\$,%#]+';
	$regex_mail = "[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+[a-zA-Z0-9\._-]+";
	
	// URL
	$ret = "";
	if (preg_match_all('/('.$regex.')/', $str, $aPattern)){
//		$aPattern[0] = array_unique($aPattern[0]);	// 重複を削除
		for ($i=0; $i<count($aPattern[0]); $i++){
			$tmp = substr($str, 0, strpos($str, $aPattern[0][$i]));
			$str = preg_replace("/^".str_replace('/', '\/', quotemeta($tmp))."/", "", $str);
			$ret .= $tmp;
			
			$buf = html_entity_decode($aPattern[0][$i]);
			
			$ret .= "<a href=\"".$aPattern[0][$i]."\" target=\"_blank\">".$buf."</a>";
			$str = preg_replace("/^".str_replace('/', '\/', quotemeta($aPattern[0][$i]))."/", "", $str);
		}
	}
	$ret .= $str;
	
	// メールアドレス
	$str = $ret;
	$ret = "";
	$str = mb_ereg_replace("：", "：&nbsp;", $str);
	if (preg_match_all("/(".$regex_mail.")/", $str, $aPatternMail)){
//		$aPatternMail[0] = array_unique($aPatternMail[0]);	// 重複を削除
		for ($i=0; $i<count($aPatternMail[0]); $i++){
			$tmp = substr($str, 0, strpos($str, $aPatternMail[0][$i]));
			$str = preg_replace("/^".str_replace('/', '\/', quotemeta($tmp))."/", "", $str);
			$ret .= $tmp;
			
			$ret .= "<a href=\"mailto:".$aPatternMail[0][$i]."\">".$aPatternMail[0][$i]."</a>";
			$str = preg_replace("/^".str_replace('/', '\/', quotemeta($aPatternMail[0][$i]))."/", "", $str);
			
			//$str = str_replace($aPatternMail[0][$i], '<a href="mailto:'.$aPatternMail[0][$i].'">'.$aPatternMail[0][$i].'</a>', $str);
		}
	}
	$ret .= $str;
	
	return $ret;
}


// メール送信関数
function send_mail($to, $from, $sender = "", $subject, $text, $bcc = "", $cc = "", $org_encode = "UTF-8")
{
	mb_language("Ja");
//	$org_encode = mb_internal_encoding();
	mb_internal_encoding("JIS");
	
//	$sender = "";
//	$cc = "kaneku@hiromori.co.jp";
	
	$text = str_replace("&yen;", "\\", $text);
	
	// JISにエンコード
	$subject = "=?ISO-2022-JP?B?".base64_encode( mb_convert_encoding($subject, "JIS", $org_encode)) ."?=";
//	$subject = mb_encode_mimeheader(mb_convert_encoding($subject, "JIS", "EUC-JP"));
	if ($sender != ""){
		$sender  = "=?ISO-2022-JP?B?".base64_encode( mb_convert_encoding($sender, "JIS", $org_encode) )."?=";
	}
	$text  = mb_convert_encoding($text, "JIS", $org_encode);
	
	// From
	$opt1 = "From: ";
	if ($sender != ""){
		$opt1 .= $sender."<";
	}
	$opt1 .= $from;
	if ($sender != ""){
		$opt1 .= ">";
	}
	$opt1 .= "\n";
	
	// Cc
	if($cc != "") {
		$opt1 .= "Cc: ".$cc."\n";
	}
	
	// Bcc
	if($bcc != "") {
		$opt1 .= "Bcc: ".$bcc."\n";
	}
	
	$opt1 .= "MIME-Version: 1.0\n";
	$opt1 .= "Content-Type: text/plain; charset=ISO-2022-JP\n";
	$opt1 .= "Content-Transfer-Encoding: 7bit\n";
	$opt1 .= "X-Mailer: AES mail system\n";
	
	$opt2 = "-f$from";

	//qmail対応(LFで統一)
	$aRegex = array("\r\n", "\r", "\n");
	$subject = str_replace($aRegex, "\n", $subject);
	$text = str_replace($aRegex, "\n", $text);
	$opt1 = str_replace($aRegex, "\n", $opt1);
	$opt1 = mb_ereg_replace("\n$", "", $opt1);
	$opt2 = str_replace($aRegex, "\n", $opt2);

	mail($to, $subject, $text, $opt1, $opt2);
	
//	error_log ($to."\n".$subject."\n".$text."\n".$opt1."\n".$opt2."\n\n", 3, CNF_VAR_PATH."mail_log/maillog.txt");
	
	mb_internal_encoding($org_encode);
	
	return 0;
}

// 内部エンコードを指定文字コードで表示する（デフォルト：PHPがEUC、HTMLがSJISの時）
function fInclude($file, $endoce_type = 'SJIS') {
	mb_internal_encoding($endoce_type);
	include($file);
	
	return 0;
}


// 日付チェック
// 引数：日付データ(ハイフン区切り)
// 戻り値：
function fChkDate($date) {
	
	$year = "";
	$month = "";
	$day = "";

	list($year, $month, $day) = explode("-", $date);

	$aLastDay = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	// 月の存在
	if($month < 1 || 12 < $month) {
		return 0;
	}

	// 閏年計算
	if($month == 2) {

		if( (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0) ) {
			$aLastDay[1]++;
		}
	}

	// 日の存在
	if( ($day < 1) || ($aLastDay[$month-1] < $day) ) {
		return 0;
	}

	return 1;
}

// 携帯メールアドレスチェック
function fChkKeitai ($sValue)
{
	$aList[0] = "ezweb.ne.jp";
	$aList[1] = "docomo.ne.jp";
	$aList[2] = "vodafone.ne.jp";
	$aList[3] = "pdx.ne.jp";
	$aList[4] = "softbank.ne.jp";
	
	for ($i=0; $i<count($aList); $i++){
		if (strpos($sValue, $aList[$i]) !== false){
//			print $aList[$i]."<BR>\n";
			return 1;
		}
	}
	
	return 0;
}

//* DB Error処理関数
function fErrDB($errno = "") {
	
	if($errno == "") {
		$errno = mysql_errno();
	}
	$strErrMsg = Error("現在このページへのアクセスは出来ません。<br>詳しくは管理者にお問い合わせください。<br><br>（DB Error: ".$errno." ）");
	
	return $strErrMsg;
}

//* SQL文用エスケープ処理
// ECUのみ推奨？SJISの時は注意
function fEscMySQL($value) {
   // Stripslashes
//   if (get_magic_quotes_gpc()) {
//      $value = stripslashes($value);
// }
   // 数値あるいは数値形式の文字列以外をクオートする
   if (!is_numeric($value)) {
       $value = mysql_real_escape_string($value);
   }
   return $value;
}

//*	携帯キャリア判定
// 戻り値：$hRet
//	['flg']		=> 0 (PC)
//				=> 1 (携帯) 
function fCarrierAnalyze($dir_pc = 'pc/', $dir_mobile = 'mob/'){
	
	$hRet = array();
	$flg_carrier = null;	// 0 => PC 
							// 1 => 携帯
							
	//* iモード（FOMA,mova）
	if ( preg_match( "/DoCoMo/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$hRet['zenkaku'] = " istyle=\"1\"";
		$hRet['hankaku_kana'] = " istyle=\"2\"";
		$hRet['hankaku_eiji'] = " istyle=\"3\"";
		$hRet['hankaku_suji'] = " istyle=\"4\"";
		$hRet['img_ext'] = "jpg";
		$hRet['flg'] = 1;
		$hRet['no'] = 1;
	}
	//* EZweb（WAP 2.0,HDML）
	elseif ( preg_match( "/UP\.Browser/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$hRet['zenkaku'] = " istyle=\"1\"";
		$hRet['zenkaku_kana'] = " istyle=\"2\"";
		$hRet['hankaku_eiji'] = " istyle=\"3\"";
		$hRet['hankaku_suji'] = " istyle=\"4\"";
		$hRet['img_ext'] = "jpg";
		$hRet['flg'] = 1;
		$hRet['no'] = 2;
	}
	//* Vodafone live!（旧3G）
	elseif ( preg_match( "/J-PHONE/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$hRet['zenkaku'] = " mode=\"hiragana\"";
		$hRet['zenkaku_kana'] = " mode=\"katakana\"";
		$hRet['hankaku_eiji'] = " mode=\"alphabet\"";
		$hRet['hankaku_suji'] = " mode=\"numeric\"";
		$hRet['img_ext'] = "png";
		$hRet['flg'] = 1;
		$hRet['no'] = 3;
	}
	//* Vodafone live!（新3G）
	elseif ( preg_match( "/Vodafone/", $_SERVER['HTTP_USER_AGENT'] ) ) {
		$hRet['zenkaku'] = " mode=\"hiragana\"";
		$hRet['zenkaku_kana'] = " mode=\"katakana\"";
		$hRet['hankaku_eiji'] = " mode=\"alphabet\"";
		$hRet['hankaku_suji'] = " mode=\"numeric\"";
		$hRet['img_ext'] = "png";
		$hRet['flg'] = 1;
		$hRet['no'] = 3;
	}
	//* Vodafone（SoftBank）
	elseif(preg_match("/SoftBank/", $_SERVER['HTTP_USER_AGENT'] )) {
		$hRet['zenkaku'] = " mode=\"hiragana\"";
		$hRet['zenkaku_kana'] = " mode=\"katakana\"";
		$hRet['hankaku_eiji'] = " mode=\"alphabet\"";
		$hRet['hankaku_suji'] = " mode=\"numeric\"";
		$hRet['img_ext'] = "jpg";
		$hRet['flg'] = 1;
		$hRet['no'] = 3;
	}
	//* その他（主にPC）
	else {
		$hRet['zenkaku'] = " style=\"ime-mode: active;\"";
		$hRet['zenkaku_kana'] = " style=\"ime-mode: active;\"";
		$hRet['hankaku_eiji'] = " style=\"ime-mode: disabled;\"";
		$hRet['hankaku_suji'] = " style=\"ime-mode: disabled;\"";
		$hRet['img_ext'] = "jpg";
		$hRet['flg'] = 0;
		$hRet['no'] = 9;
	}
	
	if ($hRet['flg'] == 1){
		$hRet['dir'] = $dir_mobile;
	}
	else {
		$hRet['dir'] = $dir_pc;
	}
	
	return $hRet;
}

//* エンコード変換(デフォルトの変換元は「SJIS」）
//		配列でも文字列でも変換する
function fEncodeConvert($InputData, $to_encode, $from_encode = "SJIS") {
	//* 配列だったら
	if(is_array($InputData)) {
		if($to_encode != "") {
			foreach($InputData as $name => $value) {
				$InputData[$name] = mb_convert_encoding($value, $to_encode, $from_encode);
//print $name."/".$value . "=>" . mb_detect_encoding($InputData[$name]) . "<br>";
			}
		}
	}
	//* 文字列だったら
	else {
		$InputData = mb_convert_encoding($InputData, $to_encode, $from_encode);
//print mb_detect_encoding($InputData) . "<br>";
	}
	return $InputData;
}

/* エラー文のソート */
function fSortMsg($strMsg, $aErrSortKey) {
	// エラーメッセージ表示順序整形
	$aErrTmp = array();
	$aErrTmp = fConvertLF($aErrTmp);
	$aErrTmp = split("\n", $strMsg);
	$strMsg = "";

	if (is_array($aErrSortKey)){
		for ($i=0; $i<count($aErrSortKey); $i++){
			$tmp_regex = "";
			for ($j=0; $j<count($aErrTmp); $j++){
				if (mb_ereg($aErrSortKey[$i], $aErrTmp[$j])){
					$tmp_regex = "/^".$aErrSortKey[$i]."/";
					$strMsg .= $aErrTmp[$j]."\r\n";
					$aErrTmp[$j] = "";
				}
			}
		}
	}
#print $strMsg;
	return $strMsg;
}
/* 改行コードを統一 */
function fConvertLF($str, $code = "\n"){
	$str = str_replace("\r", "\n", str_replace("\r\n", "\n", $str));
	if ($code == "\n") {
		return $str;
	}
	else {
		return str_replace("\n", $code, $str);
	}
}

//文字数バイト計算
# <UTF-8>
# 普通のマルチバイト	=>2バイト：あいうえお
# 普通のシングルバイト	=>1バイト：abcde
# 句読点				=>2バイト：、。「」
# 半角濁点カナ			=>2バイト：ﾊﾞｶﾞ
# 記号					=>1バイト：★☆▼◆◎○■
# </UTF-8>
function fMBStrLen($str, $encode='UTF-8') {
//$str = '■';
	$length = 0;
	$count = mb_strwidth($str, $encode);
	for ($i=0; $i<$count; $i++) {
		$s = substr($str, $i, 1);
		$l = strlen(bin2hex($s)) / 2;
		if ($l==1){
			$length++;
		}
		else {
			$length = $len + 2;
		}
	}
//print $length;
	return $length;
}

// CSV文字列を配列に分解
function csv2array($str){
	$expr = "/, *(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
	$results = preg_split($expr,trim($str));
	return preg_replace("/^\"(.*)\"$/","$1",$results);
}

/**
 * Function converts an Javascript escaped string back into a string with specified charset (default is UTF-8). 
 * Modified function from http://pure-essence.net/stuff/code/utf8RawUrlDecode.phps
 *
 * @param string $source escaped with Javascript's escape() function
 * @param string $iconv_to destination character set will be used as second paramether in the iconv function. Default is UTF-8.
 * @return string 
 */
/* 要は、Javascriptでescape関数をかました文字列の復号 */
function unescape($source, $iconv_to = 'UTF-8') {
	$decodedStr = '';
	$pos = 0;
	$len = strlen ($source);
	while ($pos < $len) {
			$charAt = substr ($source, $pos, 1);
			if ($charAt == '%') {
					$pos++;
					$charAt = substr ($source, $pos, 1);
					if ($charAt == 'u') {
							// we got a unicode character
							$pos++;
							$unicodeHexVal = substr ($source, $pos, 4);
							$unicode = hexdec ($unicodeHexVal);
							$decodedStr .= code2utf($unicode);
							$pos += 4;
					}
					else {
							// we have an escaped ascii character
							$hexVal = substr ($source, $pos, 2);
							$decodedStr .= chr (hexdec ($hexVal));
							$pos += 2;
					}
			}
			else {
					$decodedStr .= $charAt;
					$pos++;
			}
	}

	if ($iconv_to != "UTF-8") {
			$decodedStr = iconv("UTF-8", $iconv_to, $decodedStr);
	}
	
	return $decodedStr;
}

/**
 * Function coverts number of utf char into that character.
 * Function taken from: http://sk2.php.net/manual/en/function.utf8-encode.php#49336
 *
 * @param int $num
 * @return utf8char
 */
function code2utf($num){
	if($num<128)return chr($num);
	if($num<2048)return chr(($num>>6)+192).chr(($num&63)+128);
	if($num<65536)return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
	if($num<2097152)return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128) .chr(($num&63)+128);
	return '';
}


// パスワード生成
function create_password($len = 8)
{
	for ($pw = '', $i = 0; $i < $len; $i++){
		$str = base_convert((string) rand(0, 35), 10, 36);
		$pw .= (rand(0, 1) ? strtoupper($str) : $str);
	}

	return $pw;
}

// 暗号化
function encryptStrHex($id)
{
	$id = base64_encode($id);
	$encrypted = "";
	for ($i=0; $i<strlen($id); $i++){
		$chr = ord(substr($id, $i, 1));
		$encrypted .= sprintf("%02s", dechex($chr));
	}
	
	return $encrypted;
}

// 複合化
function decryptStrHex($id)
{
	$decrypted = "";
	for ($i=0; $i<strlen($id); $i=$i+2){
		$decrypted .= chr(hexdec(substr($id, $i, 2)));
	}
	$decrypted = base64_decode($decrypted);
	
	return $decrypted;
}

?>