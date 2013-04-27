<?php

// PHP5限定っす。

class WidgetForm {
	private
		$aParts = array(),
		$tagObj = array(),
		$namespace = null,
		$objForm = null,
		$objTag = array(),
		$aAllowTag = null;


	// コンストラクタ
	public function __construct($aParts, $objForm = null)
	{
		$this->aParts = $aParts;
		$this->bind($objForm);
	}

	// 要素とフォーム値をバインド
	public function bind(&$objForm = null)
	{
		$this->objForm = $objForm;
	}

	// 名前空間セット
	public function setNameSpace($namespace){
		$this->namespace = $namespace;
	}

	// タグ作成
	public function makeTag()
	{
		foreach ($this->aParts as $key => $value){
			$class_name = 'Widget'.ucwords($value['type']);
			if (class_exists($class_name)){
				// name, value設定
				$tag_name = $this->make_name($key);
				$id = (($this->namespace != '') ? $this->namespace.'_' : '').$key;
				//$default = $this->make_value($key, $tag_name);
				//$this->objTag[$key] = new $class_name($key, $tag_name, $id, $default, $value, $this->namespace);
				$this->objTag[$key] = new $class_name($key, $tag_name, $id, $this->objForm, $value, $this->namespace);
			}
		}
	}

	// タグ表示
	public function tag($name)
	{
		return $this->objTag[$name];
	}

	// 名前空間付きname作成
	private function make_name($name){
		if (preg_match("/(\[.+\])/", $name, $match)){
			$name = str_replace($match[0], "", $name);
		}
		$tag_name = (($this->namespace != '') ? $this->namespace.'[' : '').$name.(($this->namespace != '') ? ']' : '').$match[0];
		
		return $tag_name;
	}

	// 値取得
	public function getValue($name)
	{
		return ((isset($this->objForm) && $this->objForm->get($this->make_name($name)) != null) ? $this->objForm->get($this->make_name($name)) : ((isset($this->parts[$name]['default'])) ? $this->parts[$name]['default'] : ''));
	}

	// タグすべて出力
	public function tag_all()
	{
		$ret = "";

		foreach ($this->objTag as $key => $value){
			$ret .= $this->objTag[$key];
		}

		return $ret;
	}
	
	// 項目を表示用にフリーズ
	public function freeze($aAllowTag = null)
	{
		$this->aAllowTag = $aAllowTag;
		
		foreach ($this->objTag as $obj => $data){
			$this->objTag[$obj]->freeze($aAllowTag);
		}
	}
	
	// 項目をすべてhiddenに変更
	public function hidden()
	{
		foreach ($this->objTag as $obj => $data){
			$this->objTag[$obj]->makeHiddenTag();
		}
	}
	
	// 戻り値 :	指定されたタグをHTMLタグに変換したHTML
	// 引数   :	HTML
	public function allow_html_tag($html, $aAllowTag)
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

	public function _htmlescape_unhtmlescape($sValue){
		$sString = $sValue[0];
		$sString = str_replace("&lt;", "<", $sString);
		$sString = str_replace("&gt;", ">", $sString);
		$sString = str_replace("&quot;", "\"", $sString);
		
		return $sString;
	}
}

?>