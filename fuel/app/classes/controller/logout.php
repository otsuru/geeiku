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

