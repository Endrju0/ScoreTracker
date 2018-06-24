<?php

namespace app\transfer;

class User{
	public $id;
	public $login;
	public $email;
	public $role_id;
	public $party_id;
	public $role;
	public $last_login;

	public function __construct($id, $login, $email, $role_id, $party_id, $role, $last_login){
		$this->id = $id;
		$this->login = $login;
		$this->email = $email;
		$this->role_id = $role_id;
		$this->party_id = $party_id;
		$this->role = $role;
		$this->last_login = $last_login;
	}
}
