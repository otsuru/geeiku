<?php

// PHP5限定っす。

class WidgetText {
	private
		$name = null,
		$tag_name = null,
		$id = null,
		$objForm = null,
		$parts = array(),
		$namespace = null,
		$tag = null,
		$tags = array();

	// コンストラクタ
	public function __construct($name, $tag_name, $id, &$objForm, $parts, $namespace)
	{
		$this->name = $name;
		$this->tag_name = $tag_name;
		$this->id = $id;
		$this->objForm = $objForm;
		$this->parts = $parts;
		$this->namespace = $namespace;

		if ($this->parts['is_hidden']){
			$this->makeHiddenTag();
		} else {
			$this->makeTag();
		}
	}

	// タグ作成本体
	public function makeTag()
	{
		$tag  = '<input type="text"';
		$tag .= ' name="'.$this->tag_name.'"';
		$tag .= ' id="'.$this->id.'"';
//print $this->tag_name." : ".$this->parts[$this->name]['default']."<BR>\n";
		$tag .= ' value="'.((isset($this->objForm) && $this->objForm->get($this->tag_name) != null) ? $this->objForm->get($this->tag_name) : '').'"';
		$tag .= (($this->parts['option'] != '') ? ' '.$this->parts['option'] : '');
		$tag .= '>';

		$this->tag = $tag;
	}

	// Hiddenタグ作成本体
	public function makeHiddenTag()
	{
		$tag  = '<input type="hidden"';
		$tag .= ' name="'.$this->tag_name.'"';
		$tag .= ' id="'.$this->id.'"';
		$tag .= ' value="'.((isset($this->objForm) && $this->objForm->get($this->tag_name) != null) ? $this->objForm->get($this->tag_name) : ((isset($this->parts[$this->name]['default'])) ? $this->parts[$this->name]['default'] : '')).'"';
		$tag .= '>'."\n";

		$this->tag = $tag;
	}

	// タグ表示
	public function __toString()
	{
		return $this->tag;
	}
	
	// 静的表示
	public function freeze($aAllowTag)
	{
		$this->makeHiddenTag();
		if ($aAllowTag == null){
			$this->tag .= $this->objForm->get($this->tag_name);
		} else {
			$this->tag .= WidgetForm::allow_html_tag($this->objForm->get($this->tag_name), $aAllowTag);
		}
	}
}

?>