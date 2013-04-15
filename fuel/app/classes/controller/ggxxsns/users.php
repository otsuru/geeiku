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

