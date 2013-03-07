<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function sign_in()
	{
		$this->load->library('TwitterAuth');
		$url = $this->twitterauth->get_twitter_url();
		//echo'<h1>This is Test Method</h1>';
		//echo $url;
		header("Location: ".$url);exit;
	}
	
	
	public function twit_call()
	{
		$this->load->library('TwitterAuth');		
		$status = $this->twitterauth->callback();
		echo'<h1>THIS IS CALL BACK METHOD</h1>';
		if(!empty($status))
		{ 
			echo '<pre>';
			print_r($status);
		}
		else if(!$status){
			echo '<h1 style="color:red;">Invalid Request!</h1>';
		}else{
			echo '<h1 style="color:red;">'.$status.'!</h1>';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */