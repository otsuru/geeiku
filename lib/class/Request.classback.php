<?php
//=================================================================
//
//   フォームパラメータ処理クラス（PHP5系以降用）
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2005/03/30 Rev 1.0.0  Koba
//  --------------------------------------------------------------
//   コンストラクタで$_REQUEST以外も扱えるように修正
//   dump()関数追加
//   マジックメソッド「__set」「__get」による処理を追加 ※ __setの第1引数に配列は扱えず。
//   　　　　　　　　　　　　　　　 2011/01/25 Rev 2.0.0  Koba
//  --------------------------------------------------------------
//
//=================================================================

class Request {
	var $params = Array();
	
	function Request(&$target = null) {
		if ($target == null){
			$target = $_REQUEST;
		}
		
		// 通常のリクエストパラメータも同様に扱えるように
		if( is_array($target) ) {
			foreach( $target as $name => $value) {
				if (!is_array($value)){
					$value = $this->escape($value);
					$this->add($name, $value);
				} else {
					foreach ($target[$name] as $name2 => $value2){
						if (!is_array($value2)){
							$value2 = $this->escape($value2);
						}
						$this->params[$name][$name2] = $value2;
					}
				}
			}
		}
	}
	
	function escape($value) {
		$regex = "(\xEF\xA8\x91|\xE9\x84\xA7|\xE9\xAB\x99)";
		$value = htmlspecialchars($value, ENT_QUOTES);
		if(get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		//$value = rtrim($value, " 　\t\r\n\0\x0B");
		//$value = rtrim($value);
		$value = preg_replace("/( |　|\t|\r|\n)+$/", "", $value);
		$value = str_replace("\\", "&yen;", $value);
		$value = str_replace("&amp;", "&", $value);
		$value = str_replace("㈱", "(株)", $value);
		$value = str_replace("㈲", "(有)", $value);
		
		return $value;
	}

	function add($name, $data) {
		$this->params[$name] = $data;
	}
	
	// 2011-01-25 追加 by.koba
	function __set($name, $value){
		return $this->add($name, $value);
	}

	function get($name) {
		if (array_key_exists($name, $this->params)){
			return $this->params[$name];
		} else {
			return $this->getArrayValueForPath($this->params, $name, $default);
		}
	}
	
	// 2011-01-25 追加 by.koba
	function __get($name){
		return $this->get($name);
	}
	
	function exist($name) {
		if (array_key_exists($name, $this->params)){
			return true;
		} elseif ($this->getArrayValueForPath($this->params, $name, $default) !== null) {
			return true;
		} else {
			return false;
		}
	}
	
	function del($name) {
		unset ($this->params[$name]);
	}
	
	function get_all() {
		return $this->params;
	}
	
	function makehidden() {
		$hidden = "";
		
		foreach ($this->params as $key => $value){
			if (is_array($value)){
				foreach ($value as $key2 => $value2){
					$hidden .= "<input type=\"hidden\" name=\"".$key."[".$key2."]\" value=\"".$value2."\">\n";
				}
			} else {
				$hidden .= "<input type=\"hidden\" name=\"".$key."\" value=\"".$value."\">\n";
			}
		}
		
		return $hidden;
	}
	
	function toArray($namespace = ""){
		$ret = $this->params;
		if ($namespace != ""){
			unset($ret);
			foreach ($this->params as $key => $value){
				$ret[$namespace."[".$key."]"] = $value;
			}
		}
		
		return $ret;
	}
	
	// 2011-01-25 追加 by.koba
	function dump(){
		var_dump($this->params);
	}
	
	
	// Symfony 1.1 からの移植
	public static function getArrayValueForPath($values, $name, $default = null)
	{
		if (false === $offset = strpos($name, '['))
		{
			return isset($values[$name]) ? $values[$name] : $default;
		}

		if (!isset($values[substr($name, 0, $offset)]))
		{
			return $default;
		}

		$array = $values[substr($name, 0, $offset)];

		while (false !== $pos = strpos($name, '[', $offset))
		{
			$end = strpos($name, ']', $pos);
			if ($end == $pos + 1)
			{
				// reached a []
				if (!is_array($array))
				{
					return $default;
				}
				break;
			}
			else if (!isset($array[substr($name, $pos + 1, $end - $pos - 1)]))
			{
				return $default;
			}
			else if (is_array($array))
			{
				$array = $array[substr($name, $pos + 1, $end - $pos - 1)];
				$offset = $end;
			}
			else
			{
				return $default;
			}
		}
		
		return $array;
	}
}

?>