<?php

namespace app\transfer;

class User{
	public $id;
	public $login;
	public $email;
	public $role_id;
	public $party_id;
	public $role;

	public function __construct($id, $login, $email, $role_id, $party_id, $role){
		$this->id = $id;
		$this->login = $login;
		$this->email = $email;
		$this->role_id = $role_id;
		$this->party_id = $party_id;
		$this->role = $role;
	}
}
