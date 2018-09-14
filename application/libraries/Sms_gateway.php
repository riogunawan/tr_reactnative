<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_gateway
{
	/*
	library ini digunakan untuk melakukan sms reguler maupun sms masking.
	pilihan provider sms gateway antara lain : 
	- raja_sms
	- mesabot
	karena bisa memilih provider, library ini bergantung pada library dari masing2 provider : 
	- Raja_sms.php
	- Mesabot.php

	$config = array("provider"=>"raja_sms"); //pemiilhan provier
	$this->load->library('Sms_gateway', $config );
	$no = array("085245170777", "085245705154"); //jika lebih dari satu nomor yang akan dikirim
	$text = 'raja sms test';
	$this->sms_gateway->sms($no, $text);
	$response = $this->sms_gateway->response(); //respons 

	*/
	protected $ci;
	public $provider = "raja_sms";
	public $response;

	public function __construct($config = [])
	{
        $this->ci =& get_instance();
        $this->initialize($config);
	}
	public function initialize($config = [])
	{
		foreach ($config as $key => $val)
		{
			$this->{$key} = $val;
		}
	}
	public function sms($no, $text)
	{
		if ($this->provider == "mesabot") {
			// cek apakah no hp array
			if (is_array($no)) {
				$data['destination'] = $no;
			}
			else {
				$data['destination'] = $no;
			}
			$data['text'] = $text;
			$this->ci->load->library('Mesabot');
			$this->ci->mesabot->sms($data);
			$response = $this->ci->mesabot->response();
			$this->response = $response->messages['status'];
		}
		else if ($this->provider == "raja_sms") {
			$ipserver = "45.76.156.114";
			$apikey = "9deae00103ddc18f0c99517e4a63ef8b";
			$this->ci->load->library('Raja_sms');
			$this->ci->raja_sms->setIp($ipserver);
			// create header json  
			$senddata = array(
				'apikey' => $apikey,  
				'datapacket'=>array()
			);

			// cek apakah no hp array
			if (is_array($no)) {
				foreach ($no as $nomor) {
					array_push(
						$senddata['datapacket'],
						array(
						'number' => $nomor,
						'message' => $text,
						)
					);
				}
			}
			else {
				array_push(
					$senddata['datapacket'],
					array(
					'number' => trim($no),
					'message' => $text,
					)
				);
				
			}
			$this->ci->raja_sms->setData($senddata);
			$kirim = $this->ci->raja_sms->send();
			$response_json = json_decode($kirim);
			$this->response = $response_json->sending_respon[0]->globalstatustext;
		}
	}
	public function response()
	{
		return $this->response;
	}

	

}

/* End of file Sms_gateway.php */
/* Location: ./application/libraries/Sms_gateway.php */
