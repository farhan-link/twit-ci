<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**************************
Author : Farhan Abdul Shakoor
License : GNU/GPL
*/

require_once('twitteroauth/twitteroauth.php');
//require_once('config.php');

class Twitterauth extends TwitterOAuth
{
	const CONSUMER_KEY = "<YOUR CONSUMER KEY HERE>";
	const CONSUMER_SECRET = "<YOUR CONSUMER SECRET KEY HERE>";
	const OAUTH_CALLBACK = "YOUR CALLBCK URL HERE";
	
	var $request_token;
	var $url;
		
    public function __construct()
    {
        parent::__construct(self::CONSUMER_KEY,self::CONSUMER_SECRET);
    }
	
	public function connect(){
		$request_token = $this->getRequestToken(self::OAUTH_CALLBACK);
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		if($this->http_code == 200) {
			 $url = $this->getAuthorizeURL($token);	
			 return $url;		
		}else{
			return FALSE;
		}
	}
	
	protected function getQueryStringParams() {
    	parse_str($_SERVER['QUERY_STRING'], $params);
    	return $params;
	}

	public function callback(){
		$params = $this->getQueryStringParams();
		//print_r($params);die;
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET,$params['oauth_token'],$params['oauth_verifier']);
		
		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($params['oauth_verifier']);
//print_r($access_token);die;
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		$_SESSION['access_token'] = $access_token;
		
		/* Remove no longer needed request tokens */
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		//print_r($response);die;
		/* If HTTP response is 200 continue otherwise send to connect page to retry */
				

/* If method is set change API call made. Test is called by default. */

		if (200 == $connection->http_code) {
		  /* The user has been verified and the access tokens can be saved for future use */
		  $_SESSION['status'] = 'verified';
		  $content = $connection->get('account/verify_credentials');
			//print_r($content);		die;
			return $content;
		  //header('Location: ./index.php');
		} else {
		  /* Save HTTP status for error dialog on connnect page.*/
		 $this->clearsession();
		 echo 'Invalid Or Expired Token';die;
		}
		return FALSE;
	}
	
	public function get_twitter_url(){
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    		$this->clearsession();
			$url = $this->connect();
			return $url;
		}else{
			return FALSE;
		}
		//echo 'We sre';
	}
	
	public function clearsession(){
		session_start();
		session_destroy();
		//$this->connect();
	}
	
	
}

/**************************
Author : Farhan Abdul Shakoor
License : GNU/GPL
*/