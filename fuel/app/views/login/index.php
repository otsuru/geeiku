<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" />
	<title><?php echo TITLE ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('bootstrap-responsive.css'); ?>
	<?php echo Asset::css('common.css'); ?>
</head>
<body>
	<div class="container-fluid">
<?php	if(!preg_match( "/(iPhone|iPod|iPad|Android|BlackBerry)/", $_SERVER['HTTP_USER_AGENT'] ) ) {	?>
			<div class="navbar navbar-fixed-top">
			  <div class="navbar-inner">
				<div class="container">
				  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
				  </a>
				  <a class="brand" href="./"><?php echo TITLE ?></a>
					<div class="nav-collapse">
						<ul class="nav">
						</ul>
					</div><!--/.nav-collapse -->          
				</div>
			  </div>
			</div>
<?php	}else{	?>
		<h3><?php echo TITLE ?></h3>
<?php	}	?>
		<div class="row-fluid">
			<div class="span12" style="margin:20px 0px 20px;">
				
				<h5>はじめに</h5>
				このWebサービスはみんなで今日どこのゲームセンターに行くかを登録して
				「今日は誰かいるかな？」とか「あいつ今日来るかな？」といった情報をサーチして快適にゲーセンに行くためのツールです。<br />
				サービス利用にはTwitterアカウント及びTwitter認証が必要です。<br />
				ご了承頂ける場合は下記ボタンよりTwitter認証へお進みください。<br />	
				<br />
				<h4>動作環境</h4>
				PC（Firefox、Chrome、Safari)、スマートフォンです。<br />
				携帯（ガラケー）は動作確認してません！あとIEで見ると酷いことになりますのでご了承ください！
				
				<?php echo Form::open('login/form');  ?>
				<div class="actions m-top">
				<?php echo Form::submit('submit', 'Twitter認証へ'); ?>
				</div>
				<?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()); ?>
				<?php echo Form::close();  ?>
				
				
				<div class="m-top30">何かありましたら<a href="https://twitter.com/en_turuou" target="_blank">作成者：エン@en_turuou</a>までご連絡ください。<br />
					
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>
=======
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" />
	<title><?php echo TITLE ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('bootstrap-responsive.css'); ?>
	<?php echo Asset::css('common.css'); ?>
</head>
<body>
	<div class="container-fluid">
<?php	if(!preg_match( "/(iPhone|iPod|iPad|Android|BlackBerry)/", $_SERVER['HTTP_USER_AGENT'] ) ) {	?>
			<div class="navbar navbar-fixed-top">
			  <div class="navbar-inner">
				<div class="container">
				  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
				  </a>
				  <a class="brand" href="./"><?php echo TITLE ?></a>
					<div class="nav-collapse">
						<ul class="nav">
						</ul>
					</div><!--/.nav-collapse -->          
				</div>
			  </div>
			</div>
<?php	}else{	?>
		<h3><?php echo TITLE ?></h3>
<?php	}	?>
		<div class="row-fluid">
			<div class="span12" style="margin:20px 0px 20px;">
				
				<h5>はじめに</h5>
				このWebサービスはみんなで今日どこのゲームセンターに行くかを登録して
				「今日は誰かいるかな？」とか「あいつ今日来るかな？」といった情報をサーチして快適にゲーセンに行くためのツールです。<br />
				サービス利用にはTwitterアカウント及びTwitter認証が必要です。<br />
				ご了承頂ける場合は下記ボタンよりTwitter認証へお進みください。<br />	
				<br />
				<h4>動作環境</h4>
				PC（Firefox、Chrome、Safari)、スマートフォンです。<br />
				携帯（ガラケー）は動作確認してません！あとIEで見ると酷いことになりますのでご了承ください！
				
				<?php echo Form::open('login/form');  ?>
				<div class="actions m-top">
				<?php echo Form::submit('submit', 'Twitter認証へ'); ?>
				</div>
				<?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()); ?>
				<?php echo Form::close();  ?>
				
				
				<div class="m-top30">何かありましたら<a href="https://twitter.com/en_turuou" target="_blank">作成者：エン@en_turuou</a>までご連絡ください。<br />
					
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
