<?php

// PHP5限定っす。

class WidgetCheckbox {
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
		$tag = '';

		$array_cnt = count($this->parts['choices']);
		foreach ($this->parts['choices'] as $key => $value){
			$tmp_name  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name .= $this->name;
			$tmp_name .= ($array_cnt != 1) ? '_'.$key : '';
			$tmp_name .= ($this->namespace != null) ? ']' : '';

			$tmp_name_array  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name_array .= $this->name;
			$tmp_name_array .= ($array_cnt != 1 && !$this->parts['is_array']) ? '_'.$key : '';
			$tmp_name_array .= ($this->namespace != null) ? ']' : '';
			$tmp_name_array .= ($this->parts['is_array']) ? '[]' : '';

			$tag_tmp  = '';
			
			//例外(科目のみに
			if($this->name == "category"){
				$tag_tmp .= '<li>';
			}
			$tag_tmp .= '<label for="'.$this->id.'_'.$key.'" class="label_'.$this->id.'_'.$key.'">';
			$tag_tmp .= '<input type="checkbox"';
			if ($this->parts['is_array'] == true){
				//$tag_tmp .= ' name="'.$this->name.'[]"';
				$tag_tmp .= ' name="'.$tmp_name_array.'"';
				$tag_tmp .= ' value="'.$key.'"';
			} else {
				$tag_tmp .= ' name="'.$tmp_name.'"';
				$tag_tmp .= ' value="1"';
			}
			//$tag_tmp .= ' value="1"';
			$tag_tmp .= ' id="'.$this->id.'_'.$key.'"';
			if ($this->parts['is_array'] == true){
				$tmp_value = $this->objForm->get($this->tag_name);
				for ($i=0; $i<count($tmp_value); $i++){
					if ($tmp_value[$i] != "" && $tmp_value[$i] == $key){
						$tag_tmp .= ' checked';
					}
				}
			} elseif ($this->objForm->exist($tmp_name)){
				if ($this->objForm->get($tmp_name) == '1' || $this->objForm->get($tmp_name) == 't'){
					$tag_tmp .= ' checked';
				} elseif ($this->objForm->get($tmp_name) == '' || $this->objForm->get($tmp_name) == 'f'){
					$tag_tmp .= ' ';
				}
			} elseif (isset($this->parts['default'])){
				$tag_tmp .= ' checked';
			}

			if (isset($this->parts['parts_option'][$key])){
				$tag_tmp .= ' '.$this->parts['parts_option'][$key];
			}
			//$tag_tmp .= (($this->objForm->get($tmp_name) == '1' || $this->objForm->get($tmp_name) == 't') ? ' checked' : '');
			$tag_tmp .= (($this->parts['option'] != '') ? ' '.$this->parts['option'] : '');
			$tag_tmp .= '>';
			if ($value != ''){
				//$tag_tmp .= '&nbsp;';
				$tag_tmp .= $value;
			}
			$tag_tmp .= '</label>';
			if($this->name == "category"){
				$tag_tmp .= '</li>';
			}
			$tag_tmp .= ((isset($this->parts['delimiter'])) ? $this->parts['delimiter'] : '');
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
		$array_cnt = count($this->parts['choices']);
		foreach ($this->parts['choices'] as $key => $value){
			$val_flg =  0;
			$tag = "";
			$tmp_name  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name .= $this->name;
			$tmp_name .= ($array_cnt != 1) ? '_'.$key : '';
			$tmp_name .= ($this->namespace != null) ? ']' : '';

			$tmp_name_array  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name_array .= $this->name;
			$tmp_name_array .= ($array_cnt != 1 && !$this->parts['is_array']) ? '_'.$key : '';
			$tmp_name_array .= ($this->namespace != null) ? ']' : '';
			$tmp_name_array .= ($this->parts['is_array']) ? '[]' : '';

			$tag .= '<input type="hidden"';
			if ($this->parts['is_array'] == true){
				$tag .= ' name="'.$tmp_name_array.'"';
				//$tag .= ' value="'.$key.'"';
				$tmp_value = $this->objForm->get($this->tag_name);
				for ($i=0; $i<count($tmp_value); $i++){
					if ($tmp_value[$i] != "" && $tmp_value[$i] == $key){
						$tag .= ' value="'.$key.'"';
						$val_flg++;
					}
				}
				if ($val_flg == 0){
					$tag .= ' value=""';
				}
			} else {
				$tag .= ' name="'.$tmp_name.'"';
				//$tag .= ' value="1"';
				if ($this->objForm->get($tmp_name) == '1' || $this->objForm->get($tmp_name) == 't'){
					$tag .= ' value="1"';
				} else {
					$tag .= ' value=""';
				}
			}
			$tag .= ' id="'.$this->id.'_'.$key.'"';
			$tag .= (($this->parts['option'] != '') ? ' '.$this->parts['option'] : '');
			$tag .= '>'."\n";

			$this->tag .= $tag;
		}
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
			$tmp_name  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name .= $this->name;
			$tmp_name .= ($this->parts['is_array']) ? '' : '_'.$name;
			$tmp_name .= ($this->namespace != null) ? ']' : '';

			if ($this->parts['is_array'] == true){
				if (is_array($this->objForm->get($tmp_name)) && in_array($name, $this->objForm->get($tmp_name))){
					$return = "";
					$return .= '<input type="hidden"';
					$return .= ' name="'.$tmp_name.'[]"';
					$return .= ' value="'.$name.'"';
					$return .= '>'."\n";

					$return .= $this->parts['choices'][$name].$this->parts['delimiter'];
					return $return;
				}
			} else {
				if ($this->objForm->get($tmp_name) == '1' || $this->objForm->get($tmp_name) == 't'){
					$return = "";
					$return .= '<input type="hidden"';
					$return .= ' name="'.$tmp_name.'"';
					$return .= ' value="1"';
					$return .= '>'."\n";

					$return .= $this->parts['choices'][$name].$this->parts['delimiter'];
					return $return;
				}
			}
		}
	}

	// 静的表示
	public function freeze()
	{
		$this->freeze_flg = true;

		$this->tag = "";
		if (isset($this->parts['delimiter'])){
			$delimiter = $this->parts['delimiter'];
		} else {
			$delimiter = "　";
		}

		$this->makeHiddenTag();
		$array_cnt = count($this->parts['choices']);
		foreach ($this->parts['choices'] as $key => $value){
			$value = strip_tags($value);
			$value .= '<br />';

			$tmp_name  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name .= $this->name;
			$tmp_name .= ($array_cnt != 1) ? '_'.$key : '';
			$tmp_name .= ($this->namespace != null) ? ']' : '';

			$tmp_name_array  = ($this->namespace != null) ? $this->namespace.'[' : '';
			$tmp_name_array .= $this->name;
			$tmp_name_array .= ($array_cnt != 1 && !$this->parts['is_array']) ? '_'.$key : '';
			$tmp_name_array .= ($this->namespace != null) ? ']' : '';
			$tmp_name_array .= ($this->parts['is_array']) ? '[]' : '';

			if ($this->parts['is_array'] == true){
				$tmp_value = $this->objForm->get($this->tag_name);
				for ($i=0; $i<count($tmp_value); $i++){
					if ($tmp_value[$i] != "" && $tmp_value[$i] == $key){
						$this->tag .= $value;
						$this->tag .= $delimiter;
					}
				}
			} elseif ($this->objForm->exist($tmp_name)){
				if ($this->objForm->get($tmp_name) == '1' || $this->objForm->get($tmp_name) == 't'){
					$this->tag .= $value;
					$this->tag .= $delimiter;
				}
			}
			$this->tag = mb_ereg_replace($delimiter."$", "", $this->tag);
		}

		$this->tag = mb_ereg_replace($delimiter."$", "", $this->tag);
	}
}

?>