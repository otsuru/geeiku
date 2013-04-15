	
<?php echo Form::open('search/search');  ?>
	<table class="table table-striped table-bordered table-condensed" >
    <thead>
    <tr><th colospan="2">検索ページ</th></tr>
    </thead>
    <tbody>
    <tr><td width="150">ユーザー名検索：</td><td width="200"><?php echo \Form::input('keyword', Input::post('keyword'),array('class' => 'span3')); ?></td></tr>
	<tr><td>店舗：</td><td><?php echo Form::select('tenpo',Input::post('tenpo'),$select_list) ?></td></tr>
    <tr><td>ゲーム：</td><td><?php echo \Form::select('game', Input::post('game'),Controller_Search::arr_game(),array('class' => 'span3')); ?></td></tr>
    </tbody>
    </table>

<?php if (isset($html_error)): ?>
<div class="red">
<?php echo $html_error; ?>
</div>
<?php endif; ?>

	<input name="search" value="検索" type="submit" id="form_search" />			
<?php echo Form::close();  ?>
	
<?php if (isset($userdata)): ?>
	
<h3><?php echo $tenponame; ?></h3>
今日【<?php echo $tenponame; ?>】に集まるプレイヤーは<?php	echo count($userdata);	?>人です。
<?php	foreach($userdata as $key => $value){	?>
<div style="margin-top:20px;">
<img src="/ggxxsns/u/profile_img/<?php echo $userdata[$key]["user_id"]; ?>/<?php echo $userdata[$key]["profile"]["icon_name"].".".$userdata[$key]["profile"]["icon_ext"]; ?>">
<br />
<?php		echo $userdata[$key]["user_name"];	?>
<?php		echo "(".$userdata[$key]["checkin_datetime"].")<br />"; ?>
<?php		echo $userdata[$key]["comment"];	?>
</div>
<?php	}	?>	
<?php endif; ?>
