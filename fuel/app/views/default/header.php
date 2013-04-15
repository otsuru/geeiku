
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
							<li><a href="/ggxxsns/users/">Myhome</a></li>
							<li><a href="/ggxxsns/search/">検索機能</a></li>
							<li></li>
							<li><a href="/ggxxsns/logout/">Logout</a></li>
						</ul>
					</div><!--/.nav-collapse -->          
				</div>
			  </div>
			</div>
<?php	}else{	?>
<a href="/ggxxsns/users/"><input type="button" class="btn btn-danger btn-small" name="mypage" value="Myhome"></a>
<a href="/ggxxsns/search/"><input type="button" class="btn btn-danger btn-small" name="search" value="検索機能"></a>
<span class="m-left70">
<a href="/ggxxsns/logout/"><input type="button" class="btn btn-info btn-small" name="logout" value="Logout"></a>
</span>
<?php	}	?>