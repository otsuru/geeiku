<<<<<<< HEAD
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title><?php echo TITLE ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('bootstrap-responsive.css'); ?>
	<?php echo Asset::css('common.css'); ?>
	<?php echo Asset::js('jquery-1.6.1.min.js');?>
	<?php echo Asset::js('iine.js');?>
	<?php echo Asset::js('text.js');?>
</head>
<body>
	<div class="container-fluid">
		
						<?php echo $header; ?>
		
		<div class="row-fluid">
			<div class="span3">
			</div>
			<div class="span6" style="margin:20px 0px 20px;">
				

						<?php echo $content; ?>
			</div>
			<div class="span3">
			</div>
		</div>
		<div id="footer">
						<?php echo $footer; ?>
		</div>
	</div>
</body>
</html>
=======
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">
	<title><?php echo TITLE ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('bootstrap-responsive.css'); ?>
	<?php echo Asset::css('common.css'); ?>
	<?php echo Asset::js('jquery-1.6.1.min.js');?>
	<?php echo Asset::js('iine.js');?>
</head>
<body>
	<div class="container-fluid">
		
						<?php echo $header; ?>
		
		<div class="row-fluid">
			<div class="span3">
			</div>
			<div class="span6" style="margin:20px 0px 20px;">
				

						<?php echo $content; ?>
			</div>
			<div class="span3">
			</div>
		</div>
		<div id="footer">
						<?php echo $footer; ?>
		</div>
	</div>
</body>
</html>
>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
