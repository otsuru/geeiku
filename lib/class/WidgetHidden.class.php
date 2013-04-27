<?php

// PHP5限定っす。

class WidgetHidden {
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
		$tag  = '<input type="hidden"';
		$tag .= ' name="'.$this->tag_name.'"';
		$tag .= ' id="'.$this->id.'"';
		$tag .= ' value="'.$this->objForm->get($this->tag_name).'"';
		$tag .= '>'."\n";

		$this->tag = $tag;
	}

	// Hiddenタグ作成本体
	public function makeHiddenTag()
	{
		$this->makeTag();
	}

	// タグ表示
	public function __toString()
	{
		return $this->tag;
	}
	
	// 静的表示
	public function freeze()
	{
		//$this->tag = $this->makeHiddenTag();
	}
}

?>