<?php
//=================================================================
//
//   エラーチェックライブラリ
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   チェックパターンに「ZEN_KATAKANA」「REGEX」「FUNCTION」を追加
//   　　　　　　　　　　　　　　　 2010/09/14 Rev 1.0.0 koba@NP
//  --------------------------------------------------------------
//   チェックパターンに「BOOL」を追加
//   　　　　　　　　　　　　　　　 2011/01/25 Rev 1.0.1 koba@NP
//  --------------------------------------------------------------
//
//=================================================================

// エラーメッセージ作成
function error($errmsg){
	return $errmsg.'<br>'."\r\n";
}

// エラーチェック
function errCheck(&$objForm, &$IO_Table)
{
	$hRet = array();
	
	foreach ($IO_Table as $key => $value){
		//list($type, $num, $relate_enq, $relate_ans, $check) = preg_split("/ *[^\{][^,]*\}?, */", $value, 5);
		//$aChk = preg_split("/ *, */", $check);
		$chkArray = csv_string_to_array($value);
		$type = array_shift($chkArray);
		$num = array_shift($chkArray);
		$relate_enq = array_shift($chkArray);
		$relate_ans = array_shift($chkArray);
		$aChk = $chkArray;
		
		
		// エラーチェックなし時、次のループへ
		if ($aChk[0] == "0"){
			continue;
		}
		
		// エラーチェック
		for ($i=0; $i<count($aChk); $i++){
			// NULL
			if ($aChk[$i] == "NULL"){
				if (!isset($hRet[$key])){
					// TEXT
					if ($type == "TEXT"){
						if (trim($objForm->get($key)) == ""){
							$hRet[$key] = -1;
						}
					}
					
					// RADIO
					elseif ($type == "RADIO"){
						if ($objForm->get($key) == ""){
							$hRet[$key] = -1;
						}
					}
					
					// CHECKBOX
					elseif ($type == "CHECKBOX"){
						$tmp_cnt = 0;
						$tmp_key = $key;
						$tmp_key_end = '';
						if (preg_match('/([^\]]+)\]$/', $tmp_key, $aMatch)){
							$tmp_key = $aMatch[1];
							$tmp_key_end = ']';
						}
						for ($i=1; $i<=$num; $i++){
							if ($objForm->get($tmp_key."_".$i.$tmp_key_end) != ""){
								$tmp_cnt++;
							}
						}
						if ($tmp_cnt == 0){
							$hRet[$key] = -1;
						} else {
							$hRet[$key] = $tmp_cnt;
						}
					}
					
					// SELECT
					elseif ($type == "SELECT"){
						if ($objForm->get($key) == ""){
							$hRet[$key] = -1;
						}
					}
				}
			}
			
			// MAIL
			elseif ($aChk[$i] == "MAIL"){
				if (!isset($hRet[$key])){
					if (Chk_mail($objForm->get($key)) == 1){
						$hRet[$key] = "MAIL";
					}
				}
			}
			
			// NUM
			elseif ($aChk[$i] == "NUM"){
				if (!isset($hRet[$key])){
					if (Chk_num($objForm->get($key)) == 1){
						$hRet[$key] = "NUM";
					}
				}
			}
			
			// HYPHENNUM
			elseif ($aChk[$i] == "HYPHENNUM"){
				if (!isset($hRet[$key])){
					if (Chk_hyphennum($objForm->get($key)) == 1){
						$hRet[$key] = "HYPHENNUM";
					}
				}
			}
			
			// ALPHANUM
			elseif ($aChk[$i] == "ALPHANUM"){
				if (!isset($hRet[$key])){
					if (Chk_alphanum($objForm->get($key)) == 1){
						$hRet[$key] = "ALPHANUM";
					}
				}
			}
			
			// ALPHANUM
			elseif ($aChk[$i] == "ALPHA"){
				if (!isset($hRet[$key])){
					if (Chk_alpha($objForm->get($key)) == 1){
						$hRet[$key] = "ALPHA";
					}
				}
			}
			
			// FLOAT
			elseif ($aChk[$i] == "FLOAT"){
				if (!isset($hRet[$key])){
					if (Chk_float($objForm->get($key)) == 1){
						$hRet[$key] = "FLOAT";
					}
				}
			}
			
			// NULL_RELATION
			elseif ($aChk[$i] == "NULL_RELATION"){
				if (!isset($hRet[$key])){
					if ($objForm->get($relate_enq) == $relate_ans){
						if ($objForm->get($key) == ""){
							$hRet[$key] = -1;
						}
					}
				}
			}
			
			// ZEN_KATAKANA
			elseif ($aChk[$i] == "ZEN_KATAKANA"){
				if (!isset($hRet[$key])){
					if (Chk_zen_katakana($objForm->get($key)) == 1){
						$hRet[$key] = "ZEN_KATAKANA";
					}
				}
			}
			
			// HANKAKU_KATAKANA
			elseif ($aChk[$i] == "HANKAKU_KATAKANA"){
				if (!isset($hRet[$key])){
					if (Chk_hankaku_katakana($objForm->get($key)) == 1){
						$hRet[$key] = "HANKAKU_KATAKANA";
					}
				}
			}
			
			// REGEX
			elseif ($aChk[$i] == "REGEX"){
				if (!isset($hRet[$key])){
					if (preg_match('/'.$relate_enq.'/', $objForm->get($key)) == FALSE){
						$hRet[$key] = "REGEX";
					}
				}
			}
			
			// FUNCTION
			elseif ($aChk[$i] == "FUNCTION"){
				if (!isset($hRet[$key])){
					if (function_exists($relate_enq)){
						if (!call_user_func($relate_enq)){
							$hRet[$key] = "FUNCTION";
						}
					}
				}
			}
			
			// BOOL
			elseif ($aChk[$i] == "BOOL"){
				if (!isset($hRet[$key])){
					if ($relate_enq != 1){
						$hRet[$key] = "BOOL";
					}
				}
			}
		}
	}
	
	return $hRet;
}

// メールアドレスチェック
function Chk_mail($sValue)
{
	if ( !(preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+$/", $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 数字のみチェック
function Chk_num($sValue)
{
	if ( !ctype_digit($sValue) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 数字+ハイフンのみチェック
function Chk_hyphennum($sValue)
{
	if ( !(preg_match("/^[0-9-]+$/", $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 数字+アルファベットのみチェック
function Chk_alphanum($sValue)
{
	if ( !(preg_match("/^[a-zA-Z0-9]+$/", $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// アルファベットのみチェック
function Chk_alpha($sValue)
{
	if ( !(preg_match("/^[a-zA-Z]+$/", $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 数字小数点ありチェック
function Chk_float($sValue)
{
	if ( !(preg_match("/^[0-9]+(\.[0-9]+)?$/", $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 全角カタカナのみチェック
function Chk_zen_katakana($sValue)
{
	$opt = ((mb_detect_encoding($sValue) == "UTF-8") ? "u" : "");
	if ( !(preg_match("/^[ァ-ヶー　 ]+$/".$opt, $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// 半角カタカナのみチェック
function Chk_hankaku_katakana($sValue)
{
	$opt = ((mb_detect_encoding($sValue) == "UTF-8") ? "u" : "");
	if ( !(preg_match("/^[ｱ-ﾝﾞﾟ ]+$/".$opt, $sValue)) && $sValue != "" ){
		return 1;
	} else {
		return 0;
	}
}

// CSV文字列を配列に分解
function csv_string_to_array($str){
	$expr = "/, *(?=(?:[^\"]*\"[^\"]*\")*(?![^\"]*\"))/";
	$results = preg_split($expr,trim($str));
	return preg_replace("/^\"(.*)\"$/","$1",$results);
}

// 戻り値 :	指定されたタグをHTMLタグに変換したHTML
// 引数   :	HTML
function allow_html_tag($html, $aAllowTag)
{
	if (count($aAllowTag) == 0 ) return $html;
	
	foreach($aAllowTag as $sTag) {
		if (strpos($sTag, '/') === false) {
			$html = preg_replace_callback("/&lt;\/?". $sTag . "( .*?&gt;|\/?&gt;)/i", 
				"_htmlescape_unhtmlescape", $html);
		}
	}
	
	return $html; 
}

function _htmlescape_unhtmlescape($sValue){
	$sString = $sValue[0];
	$sString = str_replace("&lt;", "<", $sString);
	$sString = str_replace("&gt;", ">", $sString);
	$sString = str_replace("&quot;", "\"", $sString);
	
	return $sString;
}

?>