<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller{

	private $title = "Login";
	private $module = "login";
	private $upload_path = "./assets/upload/admin";
	private $stat = false;
	private $msg = "gagal proses";

	public function __construct () {
		parent::__construct ();
		$this->output->set_template("admin/login");
		$this->output->set_title("$this->title");
		$this->load->model("M_login");
	}

	public function index () {
		// UNTUK MENGECEK APAKAH SUDAH LOGIN
		if ($this->session->userdata('login') == true) {
			$notif = quirkNotif(true, "Sudah Login", "Selamat Datang ".$this->session->userdata('nama_lengkap'));
			$this->session->set_flashdata('msg', $notif);
			redirect('dashboard','refresh');
		}

		$data = array(
			"username" => form_input(array(
					"type" => "text",
					"class" => "form-control username",
					"name" => "username",
					"required" => "true",
					"placeholder" => "username",
				)),
			"pass" => form_input(array(
					"type" => "password",
					"class" => "form-control pass",
					"name" => "pass",
					"required" => "true",
					"placeholder" => "password",
				)),
			"title" => "BINA KAWASAN DAN PERTANAHAN",
			"subtitle" => "BIRO PEMERINTAHAN - SEKRETARIAT DAERAH PROVINSI KALIMANTAN BARAT",
			"form_aksi" => base_url("login/login_proses"),
		);

		$this->load->view("$this->module/form", $data);
	}

	public function login_proses () {

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->load->helper('security');
			$_backto = "login";

			//validasi form
			$config = array(
				array(
					"field" => "username",
					"label" => "Username",
					"rules" => "required|xss_clean",
					"errors" => array(
						"required" => "%s tidak boleh kosong"
					)
				),
				array(
					"field" => "pass",
					"label" => "Password",
					"rules" => "required|xss_clean|trim",
					"errors" => array(
						"required" => "%s tidak boleh kosong"
					)
				),
			);

			$this->form_validation->set_error_delimiters("<div class='alert alert-danger'><strong>Oh snap!</strong>", "</div>");
			$this->form_validation->set_rules($config);

			//jika validasi sukses
			if($this->form_validation->run() == TRUE) {
				$username = $this->input->post('username');
				$password = $this->input->post('pass');

				$admin = $this->M_login->getUserData($username);

				if ($admin->num_rows() > 0) {
					$admin = $admin->row();
					$exp = $admin->forgot_exp;
					$proses = false;

					if ( !empty($exp) ) {
						$now = strtotime( date("Y-m-d H:i:s") );
						$exp = strtotime($exp);

						if ($now > $exp) {
							$proses = false;
							$this->msg = "Password yang anda inputkan tidak bisa di gunakan";
						} else {
							$proses = true;
						}

					} else {
						$proses = true;
					}

					if ($proses) {
						if (password_verify($password, $admin->pass)) {
							$image_url = "assets/upload/admin/thumb/$admin->avatar";
							$image = ( empty($admin->avatar) || !file_exists(FCPATH . "$image_url")  ) ? "" : $admin->avatar;
							$data = array(
								"id_admin" => $admin->id_admin,
								"username" => $admin->username,
								"id_level" => $admin->id_level,
								"level" => $admin->level,
								"pass" => $admin->pass,
								"wilayah_id" => $admin->wilayah_id,
								"nama_lengkap" => $admin->nama_lengkap,
								"avatar" => $image,
								"login" => true,
							);

							$this->session->set_userdata($data);

							$ip = $this->input->ip_address();
							$server = $this->input->server("HTTP_USER_AGENT");

							$this->M_login->setIpAddress($username, $ip );

							$this->stat = true;
							$_backto = "dashboard";
						} else {
							$this->msg = "Maaf, nama dan atau sandi Anda salah";
						}
					}
				}
			}

			if ($this->stat) {
				$notif = quirkNotif(true, "Login Sukses", "Selamat Datang ".$admin->nama_lengkap);
				$this->session->set_flashdata('msg', $notif);
			} else {
				$notif = quirkNotif(false, "Login Gagal", $this->msg);
				$this->session->set_flashdata('msg', $notif);
			}

			redirect($_backto);
		} else {
			show_404();
		}
	}

	public function forgot () {
		$email = $this->input->post('email');
		$stat = false;
		$kode = "";

		if ( empty($email) ) {
			show_404();
		} else {
			$sql = $this->M_login->cekEmail($email);
			if ($sql->num_rows() > 0) {
				$stat = true;

				$exp = date('Y-m-d', strtotime('+1 days', strtotime( date("Y-m-d") ))) . " 00:00:00";
				$new_pass = strtoupper(substr(md5($email.time()),2,7));
				$pass = password_hash($new_pass, PASSWORD_BCRYPT, array('cost' => 12));

				$this->M_login->ubahPassword($email, $pass, $exp);
				$msg = "
					<p>Anda berhasil merubah password anda pada Aplikasi SIMPEDULI. <br/> Silahkan gunakan password berikut:</p><br/>
					<h4>$new_pass</h4>
					<span style='color: red'>Password diatas tidak akan bisa digunakan setelah tanggal <b>". konversi_tanggal($exp, "j F Y H:i:s") ."</b> <br /></span> <br />
					<span style='color: red'>Harap untuk segera mengganti password yang telah kami berikan</span> <br/>
					Terima kasih, <br/>
					Admin SIMPEDULI.
				";

				kirim_email(array(
					"to" => $email,
					"from" => "Admin SIMPEDULI",
					"subject" => "Perubahan Password",
					"msg" => $msg
				));
			}

		}

		echo json_encode(array(
			"stat" => $stat,
			"exp" => konversi_tanggal($exp, "j F Y H:i:s")
		));
	}

	public function cek_login () {
		$status_login = $this->session->userdata('login');
		$pass = $this->session->userdata('pass');
		$username = $this->session->userdata('username');

		$sql = $this->db
			->select("id_admin")
			->where("username", $username)
			->where("pass", $pass)
			->get("admin", 1);

		if (!isset($status_login) || $status_login != TRUE || $sql->num_rows() == 0) {
			$this->session->sess_destroy();
			redirect('login');
		} else {
			// redirect('dashboard');
			return true;
		}
	}

	public function terlarang ($level_forbiden) {
		if ($this->session->userdata('level') == $level_forbiden) {
			redirect('login/forbiden');
		}
	}

	public function grant ($level) {
		if (is_array($level)) {
			if (!in_array($this->session->userdata('level'), $level)) {
				$this->logout();
			}
		} else {
			if ($this->session->userdata('level') != $data) {
				$this->logout();
			}
		}
	}

	public function logout () {
		$this->session->sess_destroy();
		redirect('login');
	}

	public function forbiden () {
		$this->output->set_template('admin/default');
		$this->output->set_title($this->title);
		$data['title'] = "Forbiden Area";
		$data['subtitle'] = "Anda tidak memiliki hak akses ke halaman ini";
		$this->logout();
		$this->load->view('forbiden', $data);
	}

	public function check_captcha ($val) {
		  if ($this->recaptcha->check_answer($this->input->ip_address(), $this->input->post('recaptcha_challenge_field'), $val)) {
			return TRUE;
		  }

		$this->form_validation->set_message('check_captcha', $this->lang->line('recaptcha_incorrect_response'));
		return FALSE;
	}
}
