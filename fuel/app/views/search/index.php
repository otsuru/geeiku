	
<?php echo Form::open('search/search');  ?>

<?php if (isset($html_error)): ?>
<div class="red" style="margin-bottom:10px;">
<?php echo $html_error; ?>
</div>
<?php endif; ?>

	<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr><th colspan="2">Search</th></tr>
    </thead>
    <tbody>
    <tr><td>【ユーザー検索】<br />ユーザー名、及びIDでの検索が可能です。<br />
		<?php echo \Form::input('keyword', Input::post('keyword'),array('class' => 'input-large')); ?></td></tr>
	<tr><td>【店舗選択】<br />
			ゲームと一緒に選択すると登録したユーザーを検索可能です。<br />
		<?php	if(isset($tenpo)):		?>
		<?php echo Form::select('tenpo',$tenpo,$select_list) ?>
		<?php	else:	?>
		<?php echo Form::select('tenpo',Input::post('tenpo'),$select_list) ?>
		<?php	endif;	?>
		</td></tr>
    <tr><td>【ゲーム選択】<br />
			・店舗、ユーザー検索と組み合わせてお使い下さい。<br />
			
		<?php	if(isset($game)):		?>
		<?php echo \Form::select('game', $game,Controller_Search::arr_game(),array('class' => 'span4')); ?>
		<?php	else:	?>
		<?php echo \Form::select('game', Input::post('game'),Controller_Search::arr_game(),array('class' => 'span4')); ?>
		<?php	endif;	?>
		</td></tr>
    </tbody>
    </table>


	<input name="search" value="検　索" type="submit" id="form_search" class="btn btn-success"/>			
<?php echo Form::close();  ?>
	
<?php if (isset($userdata)): ?>
	
<?php		if (isset($tenponame)): ?>
<h3><?php echo $tenponame; ?></h3>
今日【<?php echo $tenponame; ?>】に集まるプレイヤーは<?php	echo count($userdata);	?>人です。
<?php		endif;	?>
<?php	foreach($userdata as $key => $value){	?>
<div style="margin-top:20px;">
<img src="/ggxxsns/u/profile_img/<?php echo $userdata[$key]["user_id"]; ?>/<?php echo $userdata[$key]["profile"]["icon_name"].".".$userdata[$key]["profile"]["icon_ext"]; ?>">
<br />
<?php		echo $userdata[$key]["user_name"];	?>
<?php		echo " (".$userdata[$key]["checkin_datetime"].")<br />"; ?>
<?php		if($userdata[$key]["comment"] != ""):	?>
<div class="comment">
<?php			echo "「".$userdata[$key]["comment"]."」";	?>
</div><br />
<?php		endif;	?>
<!--いいね-->
<?php	if(\Session::get('user_id') != $userdata[$key]["user_id"]):	?>
<input type="button" id="<?php		echo $userdata[$key]["user_id"];	?>"  class="btn btn-small btn-warning" value="いいね！"
<?php		if(preg_match("/;".\Session::get('user_id').";/", $userdata[$key]["iine_id"])):	?>
disabled="disabled"
<?php		endif; ?>
>
<br />
<?php	endif; ?>
<?php $iine_user = preg_replace('/,/',"　",$userdata[$key]["iine_name"]); ?>
<?php	if($iine_user != ""):	?>
<img src="/ggxxsns/assets/img/bigsmile.gif"> <span id="user_name_iine_<?php		echo $userdata[$key]["user_id"];	?>" class="textsize11 gray"></span><span class="textsize11 gray"><?php echo $iine_user; ?></span>
<?php	else: ?>
<span id="user_name_iine_<?php		echo $userdata[$key]["user_id"];	?>" class="textsize11 gray"></span>
<?php endif; ?>
<!--いいねend-->
</div>
<?php	}	?>	
<?php endif; ?>


<?php if (isset($userdata_one)): ?>
	
<?php	foreach($userdata_one as $key => $value){	?>
<div style="margin-top:20px;">
<img src="/ggxxsns/u/profile_img/<?php echo $userdata_one[$key]["id"]; ?>/<?php echo $userdata_one[$key]["icon_name"].".".$userdata_one[$key]["icon_ext"]; ?>">
<br />
<?php		echo $userdata_one[$key]["name"];	?>
</div>
<?php	}	?>	
<?php endif; ?>