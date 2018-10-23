<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends MX_Controller {

	private $title = "Crud";
	private $module = "crud";
	private $stat = false;

	public function __construct() {
		parent::__construct();
		// $this->output->set_template("crud/default");
		$this->output->set_title($this->title);
		$this->load->model("M_crud", "M_app");
	}

	public function index() {
		echo "$this->title";
	}

	public function insert () {
		$stat = false;

		$json = file_get_contents('php://input');
		$obj = json_decode($json, true);
		
		if ($json) {
			$data = array(
				'name' => $obj['name'],
				'email' => $obj['email'],
				'phone_number' => $obj['phone_number'],
			);

			$add = $this->M_app->add($data);

			if ($add) {
				echo json_encode('Insert berhasil');
			} else {
				echo json_encode('Insert gagal');
			}
		} else {
			show_404();
		}
		
	}


}

/* End of file Crud.php */
/* Location: ./application/modules/crud/controllers/Crud.php */