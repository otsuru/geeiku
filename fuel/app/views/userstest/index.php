	
<?php echo Form::open('userstest/form');  ?>

	<div class="red textsize11 box" style="margin-bottom:10px;">
	4/23：店舗(佐賀FASボウリングセンター、アミューズメントジャングル三雲)を追加いたしました。
	</div>

		<?php if (isset($message)): ?>
			<div class="red" style="margin-bottom:10px;"><?php	echo $message ?></div>
		<?php endif; ?>
			
	<table class="table table-striped table-bordered table-condensed">
    <thead>
    <tr><th>Myhome</th></tr>
    </thead>
    <tbody>
    <tr><td><img src="/ggxxsns/u/profile_img/<?php echo $id; ?>/<?php echo $icon_name.".".$icon_ext; ?>"> <?php echo $name." [ID：".$id."]"; ?></td></tr>
	<tr><td><div style="margin-bottom:5px;">【お気に入り店舗登録】</div><br />
	
		<?php	echo Form::select('fav_tenpo',$fav_tenpo,$select_list) ?>　
		<?php echo \Form::select('fav_tenpo_game', $fav_tenpo_game,Controller_Userstest::arr_game(),array('class' => 'span4')); ?>　　
		<input name="entry" value="登　録" type="submit" id="form_submit" class="btn btn btn-warning"/>
		
<?php if (isset($tenpo_u_data) && count($tenpo_u_data) != 0): ?>
<h5>お気に入り店舗情報(<?php echo $tenpo_name ?>)</h5>
今日集まるプレイヤーは<?php	echo count($tenpo_u_data);	?>人です。
<?php	foreach($tenpo_u_data as $key => $value){	?>
<?php       if($tenpo_u_data[$key]["hide"] != "1"):   ?>
<div style="margin-top:20px;">
<img src="/ggxxsns/u/profile_img/<?php echo $tenpo_u_data[$key]["user_id"]; ?>/<?php echo $tenpo_u_data[$key]["profile"]["icon_name"].".".$tenpo_u_data[$key]["profile"]["icon_ext"]; ?>">
<br />
<?php		echo $tenpo_u_data[$key]["user_name"];	?>
<?php   if($tenpo_u_data[$key]["checkin_datetime"] != DATE_Y."-".sprintf("%02d", DATE_M)."-".sprintf("%02d", DATE_D)." 23:59:00"):   ?>
<?php		echo " (".$tenpo_u_data[$key]["checkin_datetime"].")<br />"; ?>
<?php endif;    ?>
<?php	if($tenpo_u_data[$key]["comment"] != ""):	?>
<div class="comment">
<?php		echo "「".$tenpo_u_data[$key]["comment"]."」";	?>
</div>
<?php	endif; ?>
<br />

<?php	if($id != $tenpo_u_data[$key]["user_id"]):	?>
<input type="button" id="<?php		echo $tenpo_u_data[$key]["user_id"];	?>"  class="btn btn-small btn-warning" value="いいね！"
<?php		if(preg_match("/;".$id.";/", $tenpo_u_data[$key]["iine_id"])):	?>
disabled="disabled"
<?php		endif; ?>
>
<br />
<?php	endif; ?>
<?php $iine_user = preg_replace('/,/',"　",$tenpo_u_data[$key]["iine_name"]); ?>
<?php	if($iine_user != ""):	?>
<img src="/ggxxsns/assets/img/bigsmile.gif"> <span id="user_name_iine_<?php		echo $tenpo_u_data[$key]["user_id"];	?>" class="textsize11 gray"></span><span class="textsize11 gray"><?php echo $iine_user; ?></span>
<?php	else: ?>
<span id="user_name_iine_<?php		echo $tenpo_u_data[$key]["user_id"];	?>" class="textsize11 gray"></span>
<?php endif; ?>
</div>
<?php endif; ?>
<?php	}	?>
<?php endif; ?>

		</td>
	</tr>

    <tr><td><div margin-bottom="5px">【ゲーセン行くよ！ 予約・帰宅機能】</div><br />
			<?php echo \Form::select('checkin_tenpo',$tenpo_id,$select_list) ?>へ<br />
                        <?php echo \Form::select('game', $game,Controller_Userstest::arr_game(),array('class' => 'span4')); ?>をやりに<br />
                        <div id="time">                       
			<?php echo \Form::select('user_date_year', Input::post('user_date_year'),Controller_Userstest::arr_y(),array('class' => 'span3')); ?>年
			<?php echo \Form::select('user_date_m', DATE_M,  Controller_Userstest::arr_m(),array('class' => 'span2')); ?>月
			<?php echo \Form::select('user_date_d', DATE_D,Controller_Userstest::arr_d(),array('class' => 'span2')); ?>日<br />			    
			<?php echo \Form::select('user_time_h', DATE_H,  Controller_Userstest::arr_time(),array('class' => 'span2')); ?>時
			<?php echo \Form::select('user_time_m', Input::post('user_time_m'),Controller_Userstest::arr_min(),array('class' => 'span2')); ?>分<br />
                        </div> 
			<?php echo \Form::input('comment',$comment,array('id' => 'textbox'));	?><br />
                        
                        <?php echo \Form::checkbox('showtime', Input::post('showtime'),array('id' => 'showtime')); ?>到着時間詳細
			<?php echo \Form::checkbox('twit_connect', Input::post('twit_connect'),array('class' => '')); ?>Twitterへ投稿する<br />
			<div style="margin:10px 0px 5px;">
			<?php	if ($yoyaku_flg != 0):	?>
			<input name="checkin" value="行くよ！" type="submit" id="form_checkin" class="btn btn-primary"/>
			<?php	elseif($yoyaku_flg == 0):	?>
			<input name="checkin" value="行くよ！" type="submit" id="form_checkin" class="btn btn-warning"/>	
			<?php	endif; ?>
			<input name="checkin_hide" value="行くかも（匿名登録）" type="submit" id="form_checkin" class="btn btn-warning"/>
			</div>
			<input name="reset" value="帰　宅" type="submit" id="form_reset" class="btn btn-info" />	
		</td>
	</tr>
	<tr><td><div style="margin-bottom:5px;">【誰かいるかな？検索】</div><br />
		<?php	echo Form::select('search_tenpo', $fav_tenpo,$select_list) ?>　
		<?php echo \Form::select('search_game', $fav_tenpo_game,Controller_Userstest::arr_game(),array('class' => 'span4')); ?>　　
		<input name="submit" value="検　索" type="submit" id="form_submit" class="btn btn-success"/>
		</td></tr>
    </tbody>
    </table>


<div class="center m-top80 textsize11">
	店舗を追加したい場合や何か問題があった場合は<br />
	<a href="https://twitter.com/en_turuou" target="_blank">@en_turuou</a>までご連絡ください。<br />
</div>
<?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()); ?>
<?php echo Form::close();  ?>

