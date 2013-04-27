<<<<<<< HEAD
<?php
/**
 * APPPATH/classes/controller/logout.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/02/12
 */
class Controller_Logout extends \Fuel\Core\Controller
{	
	
	public function action_index()
	{
		\Cookie::delete('fuelcid');
		\Session::destroy();
		
		return Response::forge(View::forge('login/index'));
		
	}
}

=======
<?php
/**
 * APPPATH/classes/controller/logout.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/02/12
 */
class Controller_Logout extends \Fuel\Core\Controller
{	
	
	public function action_index()
	{
		\Cookie::delete('fuelcid');
		\Session::destroy();
		
		return Response::forge(View::forge('login/index'));
		
	}
}

>>>>>>> 5035f25efbdd9bc0c2f4e7c1b2b81e6299cce8ce
