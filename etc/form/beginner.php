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

//質問
$widget['question'] = array(
	'type'		=>	'checkbox',
	'choices'	=>	$cst_question,
	'option'	=>	'',
);

//ミカド初心者対戦会に初参加かどうか？
$widget['exp'] = array(
	'type'		=>	'radio',
	'choices'	=>	array('1' => 'はい','2' => 'いいえ'),
	'option'	=>	'',
);

//練習台を使用したいかどうか？
$widget['prac'] = array(
	'type'		=>	'radio',
	'choices'	=>	array('1' => 'はい','2' => 'いいえ'),
	'option'	=>	'',
);
// メッセージ
$widget['message'] = array(
	'type'		=>	'textarea',
	'option'	=>	'class="ja line_clr " style="" cols="30" rows="5" maxlength="200"',
);


// メッセージ2
$widget['message2'] = array(
	'type'		=>	'textarea',
	'option'	=>	'class="ja line_clr " style="" cols="30" rows="5" maxlength="200"',
);

?>