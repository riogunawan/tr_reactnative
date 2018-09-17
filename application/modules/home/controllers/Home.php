<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

	private $title = "Home";
	private $module = "home";
	// private $upload_path = "./assets/upload/admin";
	private $stat = false;

	public function __construct() {
		parent::__construct();
		// Modules::run("login/cek_login");
		// $this->output->set_template("admin/default");
		$this->output->set_title($this->title);
		$this->load->model("M_home", "M_app");
	}

	public function index () {
		$this->output->unset_template();
		$data = array(
				"title" => $this->title,
				"subtitle" => "Tabel Data",
				"link_add" => site_url("$this->module/form"),
			);

		$this->output->append_title(@$data['subtitle']);
		$this->load->view("$this->module/data", $data);
	}

	public function insert() {
		$json = file_get_contents("php://input");
		$obj = json_decode($json, true);

		$data = array(
			'name' => $obj['name'],
			'email' => $obj['email'],
			'phone_number' => $obj['phone_number'],
		);

		$add = $this->M_app->add($data);
		if ($add) {
			echo json_encode('Insert Successfully');
		} else {
			echo json_encode('Insert field');
		}
		
	}

}

/* End of file Home.php */
/* Location: ./application/modules/home/controllers/Home.php */