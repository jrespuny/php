<?php 

require 'app/User.php';
require 'app/Validator.php';
require 'app/Helper.php';

$rules = array('email' => 'required|email', 'password' => 'required|min:8');
$data = array('email' => 'yes@yes.com', 'password' => '123456789');

$validator = new Validator();
if ($validator->validate($data, $rules) == true) {
	$joost = new User();
	$joost->setEmail($data['email'])
		  ->setPassword(getHash($data['password']));
	var_dump($joost);
} else {
	var_dump($validator->getErrors());
}

 ?>
