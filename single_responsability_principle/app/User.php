<?php 

class User {
	private $email;
	private $password;

	public function login()
	{
		return 'Logging in ...';
	}

	public function logout()
	{
		return 'Loggin out ...';
	}

	public function setPassword($string)
	{
		$this->password = $string;
		return $this;
	}

	public function setEmail($string)
	{
		$this->email = $string;
		return $this;
	}

	public function getEmail()
	{
		return $this->email;
	}
}

 ?>