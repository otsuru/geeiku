<?php

// PHP5限定っす。

class WidgetSelect {
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
		$tmp_value = ((isset($this->objForm) && $this->objForm->get($this->tag_name) != null) ? $this->objForm->get($this->tag_name) : ((isset($this->parts[$this->name]['default'])) ? $this->parts[$this->name]['default'] : ''));

		$tag  = '<select';
		$tag .= ' name="'.$this->tag_name.(($this->parts["is_array"] == true) ? '[]' : '').'"';
		$tag .= ' id="'.$this->id.'"';
		$tag .= (($this->parts['option'] != '') ? ' '.$this->parts['option'] : '');
		$tag .= '>'."\r\n";
		foreach ($this->parts['choices'] as $key => $value){
			$tag .= '<option value="'.$key.'"';
			if (is_array($tmp_value)){
				for ($i=0; $i<count($tmp_value); $i++){
					if ($tmp_value[$i] != "" && $tmp_value[$i] == $key){
						$tag .= ' selected';
					}
				}
			} else {
				$tag .= (($tmp_value != "" && $tmp_value == $key) ? ' selected' : '');
			}
			$tag .= '>';
			$tag .= $value;
			$tag .= '</option>'."\r\n";
		}
		$tag .= '</select>'."\r\n";

		$this->tag = $tag;
	}

	// Hiddenタグ作成本体
	public function makeHiddenTag()
	{
		$tmp_value = ((isset($this->objForm) && $this->objForm->get($this->tag_name) != null) ? $this->objForm->get($this->tag_name) : ((isset($this->parts[$this->name]['default'])) ? $this->parts[$this->name]['default'] : ''));

		if ($this->parts["is_array"] == true){
			for ($i=0; $i<count($tmp_value); $i++){
				$tag .= '<input type="hidden"';
				$tag .= ' name="'.$this->tag_name.'[]"';
				$tag .= ' id="'.$this->id.'"';
				$tag .= ' value="'.$tmp_value[$i].'"';
				$tag .= '>'."\n";
			}
		} else {
			$tag  = '<input type="hidden"';
			$tag .= ' name="'.$this->tag_name.'"';
			$tag .= ' id="'.$this->id.'"';
			$tag .= ' value="'.$tmp_value.'"';
			$tag .= '>'."\n";
		}

		$this->tag = $tag;
	}

	// タグ表示
	public function __toString()
	{
		return $this->tag;
	}

	// 静的表示
	public function freeze()
	{
		$this->makeHiddenTag();
		if (!is_array($this->objForm->get($this->tag_name)) && $this->objForm->get($this->tag_name) != ""){
			$this->tag .= $this->parts['choices'][$this->objForm->get($this->tag_name)];
		}
	}
}

?>