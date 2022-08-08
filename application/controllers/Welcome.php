<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Welcome extends CI_Controller
{
 
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function test()
	{
		$payload = array(
			"iss" => "http://example.org",
			"aud" => "http://example.com",
			"iat" => 1356999524,
			"nbf" => 1357000000
		);

		$viva = '214748';

		$jwt = JWT::encode($viva, APP_KEY);
		$decoded = JWT::decode($jwt, APP_KEY, array('HS256'));

		print_r(md5($viva));
	}

	


}
