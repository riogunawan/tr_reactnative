<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_crud extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function data() {
		return $this->db->get('users');
	}

	public function add ($data) {
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

}

/* End of file M_crud.php */
/* Location: ./application/modules/crud/models/M_crud.php */