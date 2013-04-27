<?php
/**
 * APPPATH/classes/controller/users.php
 *
 * @extends \Fuel\Core\Controller
 *
 * @author otsuru
 * created at 2013/01/14
 */
class Controller_Resourcerest extends \Fuel\Core\Controller_Rest
{
	
	 public function before()
    {
		parent::before();
		\Package::load('twitter');
		
		if(\Session::get('user_id') == ""){
			\Response::redirect(SITE_URL.'login');
		}
		
    }
    public function post_create()
    {
        $name = $_POST['key'];
		$account = \Model_User::get_with_relation_by_id(\Session::get('user_id'));
		$checkin = \Model_Checkin::find_user($name);	
		$data = array();
		$data['iine_name'] = $checkin->iine_name;
		$data['iine_id'] = $checkin->iine_id;
		$data['user_id'] = $checkin->user_id;
		$data['name'] = $account->name;
		$checkin_new = \Model_Checkin::find_user($data['user_id']);

		try
		{
			$checkin_new->iine_name = $account->name.",".$data['iine_name'];
			$checkin_new->iine_id = $data['iine_id'].";".$account->id.";";
			$checkin_new->save();
		}
		catch(\Database_Exception $e){
			$data = array('key' => "エラー");
		}
        $data['key'] = $name;
		return $this->response($data, 200);
    }
}

