<?php

//基本情報
//エントリー名
$widget['name'] = array(
	'type'		=>	'text',
	'option'	=>	'class="ja line_clr contents_else" style="" size="30" maxlength="30"',
);

//キャラクタ一覧
$widget['chara'] = array(
	'type'		=>	'select',
	'choices'	=>	$cst_form_chara,
	'option'	=>	'style="width:130px;"',
);

// メッセージ
$widget['message'] = array(
	'type'		=>	'textarea',
	'option'	=>	'class="ja line_clr " style="" cols="30" rows="5" maxlength="200"',
);


?>