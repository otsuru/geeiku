<?php

//基本情報

//チーム名
$widget['team_name'] = array(
	'type'		=>	'text',
	'option'	=>	'class="ja line_clr contents_else" style="" size="30" maxlength="30"',
);

//エントリー名
$widget['name_1'] = array(
	'type'		=>	'text',
	'option'	=>	'class="ja line_clr contents_else" style="" size="30" maxlength="30"',
);

$widget['name_2'] = array(
	'type'		=>	'text',
	'option'	=>	'class="ja line_clr contents_else" style="" size="30" maxlength="30"',
);

$widget['name_3'] = array(
	'type'		=>	'text',
	'option'	=>	'class="ja line_clr contents_else" style="" size="30" maxlength="30"',
);

//キャラクタ一覧
$widget['chara_1'] = array(
	'type'		=>	'select',
	'choices'	=>	$cst_form_chara,
	'option'	=>	'style="width:130px;"',
);
$widget['chara_2'] = array(
	'type'		=>	'select',
	'choices'	=>	$cst_form_chara,
	'option'	=>	'style="width:130px;"',
);
$widget['chara_3'] = array(
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