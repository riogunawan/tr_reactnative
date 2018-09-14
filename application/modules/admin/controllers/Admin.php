<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

	private $title = "Manajemen Admin";
	private $module = "admin";
	private $upload_path = "./assets/upload/admin";
	private $stat = false;

	public function __construct() {
		parent::__construct();
		Modules::run("login/cek_login");
		$this->output->set_template("admin/default");
		$this->output->set_title($this->title);
		$this->load->model("M_admin", "M_app");
	}

	public function index () {
		// OTHERS CSS & JS
			// SELECT2
			$this->output->css('assets/themes/admin/plugins/select2/css/select2.min.css');
			$this->output->css('assets/themes/admin/plugins/select2/css/select2-bootstrap.min.css');
			$this->output->js('assets/themes/admin/plugins/select2/js/select2.min.js');
			
			// JQuery DataTable Css
			$this->output->css('assets/themes/admin/plugins/datatable/media/css/dataTables.bootstrap.css');
			$this->output->js('assets/themes/admin/plugins/datatable/media/js/jquery.dataTables.min.js');
			$this->output->js('assets/themes/admin/plugins/datatable/media/js/dataTables.bootstrap.min.js');
		// CLOSE

		//CUSTOM CS JS
		$this->output->script_foot("$this->module/js/data.js");

		$data = array(
				"title" => $this->title,
				"subtitle" => "Tabel Data",
				"link_add" => site_url("$this->module/form"),
				"filter" => array(
					"username" => form_input(array(
						"name" => "username",
						"class" => "form-control username",
						"type" => "text",
						"placeholder" => "cari username...",
					)),
					"nama_lengkap" => form_input(array(
						"name" => "nama_lengkap",
						"class" => "form-control nama_lengkap",
						"type" => "text",
						"placeholder" => "cari Nama...",
					)),
					"id_level" => form_select(array(
						"config" => array(
							"name" => "id_level",
							"class" => "form-control s2 id_level",
						),
						"list" => array(
							"db" => array(
								'id' => "id_level",
								'title' => "level",
								'table' => "admin_level",
							)
						),
					)),
					"kabupaten" => form_select(array(
						"config" => array(
							"name" => "kabupaten",
							"data-target" => "kecamatan",
							"class" => "form-control s2 kabupaten",
						),
						"list" => array("" => "") + $this->M_app->getWilayah("kabupaten"),
					)),
				),
			);

		$this->output->append_title(@$data['subtitle']);
		$this->load->view("$this->module/data", $data);
	}

	public function data () {
		$this->output->unset_template();
		header("Content-type: application/json");
		if (
			isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
		) {
			echo $this->M_app->data($this->input->post());
		}
		return;
	}

	public function form ($id = 0) {
		$this->_formAssets();

		// custom
		$this->output->script_foot("$this->module/js/form.js");

		if ($id > 0) {
			$this->edit($id);
		} else {
			$this->add();
		}
	}

	private function _formAssets () {
		// SELECT2
		$this->output->css('assets/themes/admin/plugins/select2/css/select2.min.css');
		$this->output->css('assets/themes/admin/plugins/select2/css/select2-bootstrap.min.css');
		$this->output->js('assets/themes/admin/plugins/select2/js/select2.min.js');

		// FILEINPUT
		$this->output->css('assets/themes/admin/plugins/file-input/css/fileinput.css');
		$this->output->css('assets/themes/admin/plugins/file-input/css/custom-file-input.css');
		$this->output->js('assets/themes/admin/plugins/file-input/js/fileinput.js');
		$this->output->js('assets/themes/admin/plugins/file-input/themes/fa/theme.js');
	}

	private function add () {
		$data = $this->_formInputData(array(
			'subtitle' => 'Tambah Data',
			"aksi" => base_url("$this->module/add_proses"),
		));

		$this->load->view("$this->module/form", $data);
	}

	private function edit ($id) {
		$sql = $this->M_app->cekid($id);

		if ($sql->num_rows() > 0) {
			$val = $sql->row();

			$avatar = (file_exists(FCPATH . "$this->upload_path/$val->avatar")) ? $val->avatar : "";

			$data = $this->_formInputData(array(
				"subtitle" => "Edit Data",
				"aksi" => base_url("$this->module/edit_proses"),
				"id" => $id,
				"avatar" => $avatar,
				"username" => $val->username,
				"id_level" => $val->id_level,
				"nama_lengkap" => $val->nama_lengkap,
				"wilayah_id" => $val->wilayah_id,
			));

			$this->load->view("$this->module/form", $data);
		} else {
			show_404();
		}
	}

	public function add_proses () {
		$this->output->unset_template();
		$this->_rules();
		$back = "$this->module/form";
		$submsg = "Gagal di proses";

		if ($this->input->post()) {
			if (!$this->form_validation->run()) {
				$submsg = $this->_formPostProsesError();
			} else {
				$avatar = "";
				if ( !empty($_FILES['avatar']['name']) && isset($_FILES['avatar']['name']) ) {
					$file_element_name = 'avatar';
					$user_upload_path = $this->upload_path.'/';

					$upload = $this->_upload($file_element_name, $user_upload_path);
					$avatar = ($upload == "") ? $avatar : $upload;
				}

				$data = $this->_formPostInputData($avatar);
				$add = $this->M_app->add($data);
				if ($add) {
					$this->stat = true;
					$back = "$this->module";
					$submsg = "Proses Berhasil";
				}
			}
			$this->_notif($back, $submsg);
		} else {
			show_404();
		}
	}

	public function edit_proses () {
		$this->output->unset_template();
		$id = $this->input->post("id");

		$sql = $this->M_app->cekId($id);

		if (
			$this->input->post() AND
			$sql->num_rows() > 0
		) {
			$this->_rules();
			$back = "$this->module/form/$id";
			$submsg = "Gagal di proses";

			if (!$this->form_validation->run()) {
				$submsg = $this->_formPostProsesError();
			} else {
				$val = $sql->row();

				$avatar = $val->avatar;

				if ( !empty($_FILES['avatar']['name']) && isset($_FILES['avatar']['name']) ) {
					$file_element_name = 'avatar';
					$user_upload_path = $this->upload_path.'/';

					$upload = $this->_upload($file_element_name, $user_upload_path, $avatar);
					$avatar = ($upload == "") ? $avatar : $upload;
				}

				$data = $this->_formPostInputData($avatar);
				$edit = $this->M_app->edit($id, $data);
				if ($edit) {
					$this->stat = true;
					$back = "$this->module";
					$submsg = "Proses Berhasil";
				}
			}
			$this->_notif($back, $submsg);
		} else {
			show_404();
		}
	}

	public function delete_proses () {
		$this->output->unset_template();

		$id = $this->input->post('id');
		$sql = $this->M_app->cekId($id);

		if ($sql->num_rows()) {
			$val = $sql->row();

			if ($val->avatar != "") {
				unlink("{$this->upload_path}/thumb/$val->avatar");
				unlink("{$this->upload_path}/$val->avatar");
			}

			$del = $this->M_app->delete($id);

			if ($del) {
				$this->stat = true;
			}

			echo json_encode(array(
				"stat" => $this->stat
			));
		} else {
			show_404();
		}
	}

	public function _upload($file_element_name = "", $user_upload_path = "", $image = "") {
		$config['upload_path'] = $user_upload_path;
		$config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
		$config['max_size']  = 1024 * 3;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload');
		$this->upload->initialize($config);

		$file_name = "";

		if ($this->upload->do_upload($file_element_name)){
			$data_upload = $this->upload->data();
			$file_name = $data_upload["file_name"];

			if ($file_element_name == 'avatar') {
				$config_resize['image_library'] = 'gd2';
				$config_resize['maintain_ratio'] = TRUE;
				$config_resize['master_dim'] = 'height';
				$config_resize['quality'] = "100%";
				$config_resize['source_image'] = $user_upload_path ."/". $file_name;
				$config_resize['new_image'] = "$user_upload_path/thumb/";
				$config_resize['width'] = 500;
				$config_resize['height'] = 500;

				$this->load->library('image_lib', $config_resize);

				if ( !$this->image_lib->resize() ) {
					$back = "$this->module";
					$submsg = "Resize File Failed";
					$this->stat = false;
					$this->_notif($back, $submsg);
				}
				
				if ($image != "") {
					unlink("{$user_upload_path}/thumb/$image");
				}
			}

			if ($image != "") {
				unlink("{$user_upload_path}/$image");
			}
		} else {
			$back = "$this->module";
			$submsg = "Upload File Failed";
			$this->stat = false;
			$this->_notif($back, $submsg);
		}

		return $file_name;
	}

	private function _formInputData ($data = array()) {
		$this->output->append_title($data['subtitle']);

		return array(
			"title" => $this->title,
			"subtitle" => $data['subtitle'],
			"link_back" => site_url($this->module),
			"form_action" => $data['aksi'],

			"avatar" => @$data['avatar'],

			"input" => array(
				"hide" => array(
					"id" => form_input(array(
						"type" => "hidden",
						"name" => "id",
						"class" => "id",
						"value" => @$data['id'],
					))
				),

				"username" => form_input(array(
					"name" => "username",
					"value" => @$data['username'],
					"class" => "form-control username",
					"type" => "text",
					"placeholder" => "username...",
				)),
				"nama_lengkap" => form_input(array(
					"name" => "nama_lengkap",
					"value" => @$data['nama_lengkap'],
					"class" => "form-control nama_lengkap",
					"type" => "text",
					"placeholder" => "nama lengkap...",
				)),
				"pass" => form_input(array(
					"name" => "pass",
					"class" => "form-control pass",
					"type" => "password",
					"placeholder" => "password...",
				)),
				"pass_confirm" => form_input(array(
					"name" => "pass_confirm",
					"class" => "form-control pass_confirm",
					"type" => "password",
					"placeholder" => "konfirmasi password...",
				)),
				"id_level" => form_select(array(
					"config" => array(
						"name" => "id_level",
						"class" => "form-control s2 id_level",
					),
					"list" => array(
						"db" => array(
							'id' => "id_level",
							'title' => "level",
							'table' => "admin_level",
						)
					),
					"selected" => @$data['id_level'],
				)),
				"kabupaten" => form_select(array(
					"config" => array(
						"name" => "kabupaten",
						"class" => "form-control s2 kabupaten",
					),
					"list" => array("" => "") + $this->M_app->getWilayah("kabupaten"),
					"selected" => @$data['wilayah_id'],
				)),
			)
		);
	}

	private function _formPostInputData ($avatar = "") {
		$username = $this->input->post("username", TRUE);
		$nama_lengkap = $this->input->post("nama_lengkap");
		$id_level = $this->input->post("id_level");
		$wilayah_id = ($id_level != 2) ? "" : $this->input->post("kabupaten");

		$data = array(
			"username" => $username,
			"id_level" => $id_level,
			"wilayah_id" => $wilayah_id,
			"avatar" => $avatar,
			"nama_lengkap" => $nama_lengkap,
			"verification_code" => strtoupper(substr(md5($username.time()),2,7)),
			"ip_address" => $this->input->ip_address(),
		);

		if (!empty($this->input->post("pass"))) {
			$data += array(
					"pass" => password_hash($this->input->post("pass"), PASSWORD_BCRYPT, array("cost" => 12)),
				);
		}

		$id = $this->input->post("id");
		if ($id == $this->session->userdata('id_admin') ) {
			$new_sess = array(
					"nama_lengkap" => $nama_lengkap,
					"wilayah_id" => $wilayah_id,
				);

			if ($avatar != "") {
				$image = "$avatar";
				$new_sess += array(
					"avatar" => $image,
				);
			}

			if ($id_level != "") {
				$level = $this->db
							->where('id_level', $id_level)
							->get('admin_level', 1)->row()->level;

				$new_sess += array(
					"level" => $level,
				);
			}

			$this->session->set_userdata($new_sess);
		}

		return $data;
	}

	private function _formPostProsesError () {
		$err = "";

		if(form_error("username")) {
			$err .= form_error("username");
		}
		if(form_error("nama_lengkap")) {
			$err .= form_error("nama_lengkap");
		}
		if(form_error("pass")) {
			$err .= form_error("pass");
		}
		if(form_error("pass_confirm")) {
			$err .= form_error("pass_confirm");
		}
		if(form_error("id_level")) {
			$err .= form_error("id_level");
		}
		if(form_error("kabupaten")) {
			$err .= form_error("kabupaten");
		}

		return $err;
	}

	private function _rules () {
		$this->load->helper('security');
		$this->load->library('form_validation');

		$config = array(
			array(
				"field" => "username",
				"label" => "Username",
				"rules" => "required",
				"errors" => array(
					"required" => "%s tidak boleh kosong"
				)
			),
			array(
				"field" => "nama_lengkap",
				"label" => "Nama Lengkap",
				"rules" => "required",
				"errors" => array(
					"required" => "%s tidak boleh kosong"
				)
			),
			array(
				"field" => "pass",
				"label" => "Password",
				"rules" => "trim",
			),
			array(
				"field" => "pass_confirm",
				"label" => "Konfirmasi Password",
				"rules" => "matches[pass]",
				"errors" => array(
					"matches" => "Konfirmasi Password tidak sama dengan Password"
				)
			),
			array(
				"field" => "id_level",
				"label" => "Level",
				"rules" => "required",
				"errors" => array(
					"required" => "%s tidak boleh kosong"
				)
			),
		);

		if ($this->input->post("id_level") == 2) {
			$config = array(
				array(
					"field" => "kabupaten",
					"label" => "Kabupaten",
					"rules" => "required",
					"errors" => array(
						"required" => "%s tidak boleh kosong"
					)
				),
			);
		}

		$this->form_validation->set_error_delimiters("<div class='alert alert-danger'><strong>Oh snap!</strong>", "</div>");
		$this->form_validation->set_rules($config);
	}

	private function _notif ($back, $submsg = "") {
		if ($this->stat) {
			$this->session->set_flashdata( "msg", quirkNotif(true, "Sukses", $submsg) );
		} else {
			$this->session->set_flashdata( "msg", quirkNotif(false, "Gagal", $submsg) );
		}

		redirect($back);
	}

	public function check() {
		$this->output->unset_template();
		if($this->input->is_ajax_request()){
			if($this->input->post()){
				$username = $this->input->post('username');
				$id = $this->input->post('id');
				if ($username) {
					$check = $this->M_app->check_username($username, $id);
					if($check){
						echo "false";
					}else{
						echo "true";
					}
				}
			}
		}
	}

}

/* End of file Admin.php */
/* Location: ./application/modules/admin/controllers/Admin.php */