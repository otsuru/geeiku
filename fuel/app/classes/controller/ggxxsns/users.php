<<<<<<< HEAD
<?php
/**
 * APPPATH/classes/controller/users.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/01/14
 */
class Controller_Ggxxsns_Users extends Controller
{	
	public function action_index()
	{	
		$twitter_user = Twitter::get('account/verify_credentials');
$data = array(
    'screen_name' => $twitter_user->screen_name
);
		return Response::forge(View::forge('users/index',$data));
	}
}

=======
<?php
/**
 * APPPATH/classes/controller/users.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/01/14
 */
class Controller_Ggxxsns_Users extends Controller
{	
	public function action_index()
	{	

		return Response::forge(View::forge('users/index',$data));
	}
}

>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
