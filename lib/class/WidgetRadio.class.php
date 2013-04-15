<?php

// PHP5限定っす。

class WidgetRadio {
	private
		$name = null,
		$tag_name = null,
		$id = null,
		$objForm = null,
		$parts = array(),
		$namespace = null,
		$tag = null,
		$tags = array(),
		$freeze_flg = false;

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
		$tag = '';

		foreach ($this->parts['choices'] as $key => $value){
			$tag_tmp  = '<label for="'.$this->id.'_'.$key.'" class="label_'.$this->id.'_'.$key.'">';
			$tag_tmp .= '<input type="radio"';
			$tag_tmp .= ' name="'.$this->tag_name.'"';
			$tag_tmp .= ' id="'.$this->id.'_'.$key.'"';
			$tag_tmp .= ' value="'.$key.'"';
			$tag_tmp .= (($tmp_value == $key) ? ' checked' : '');
			$tag_tmp .= (($this->parts['option'] != '') ? ' '.$this->parts['option'] : '');
			$tag_tmp .= '>';
			//$tag_tmp .= '&nbsp;';
			$tag_tmp .= $value;
			$tag_tmp .= '</label>';
			$tag_tmp .= (($this->parts['delimiter'] != '') ? $this->parts['delimiter'] : '');
			$tag_tmp .= "\r\n";

			$this->tags[$key] = $tag_tmp;
			$tag .= $tag_tmp;
		}
		if (isset($this->parts['delimiter']) && isset($this->parts['delimiter_end'])){
			if ($this->parts['delimiter_end'] == false){
				$tag = preg_replace("/".preg_quote($this->parts['delimiter'], '/')."\r\n$/", "", $tag);
			}
		}

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

	// 個別タグ表示
	public function parts($name)
	{
		if ($this->freeze_flg == false){
			return $this->tags[$name];
		} else {
			$tmp_value = ((isset($this->objForm) && $this->objForm->get($this->tag_name) != null) ? $this->objForm->get($this->tag_name) : ((isset($this->parts[$this->name]['default'])) ? $this->parts[$this->name]['default'] : ''));

			if ($tmp_value == $name){
				$this->makeHiddenTag();
				if ($name != ""){
					$this->tag .= $this->parts['choices'][$name];
				}
				return $this->tag;
			}
		}
	}

	// 静的表示
	public function freeze()
	{
		$this->freeze_flg = true;
		$this->makeHiddenTag();
		if ($this->objForm->get($this->tag_name) != ""){
			$this->tag .= $this->parts['choices'][$this->objForm->get($this->tag_name)];
		}

	}
}

?>