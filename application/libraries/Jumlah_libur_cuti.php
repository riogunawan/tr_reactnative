<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * cara menggunakannya
 * 1. untuk menghitung jumlah cuti
 * $this->load->library('HitungLibur');
 * $this->HitungLibur
 *      ->rentangTanggal($tanggal_mulai, $tanggal_selesai)
 *      ->jumlahCuti();
 *
 * 2. menghitung jumlah cuti tanpa hari sabtu dan minggu
 * $this->HitungLibur
 *      ->rentangTanggal($tanggal_mulai, $tanggal_selesai)
 *      ->tanpaAkhirPekan()
 *      ->jumlahCuti();
 *
 * 3. jika menyertakan taggal libur nasional dan cuti bersama
 * $this->HitungLibur
 *      ->rentangTanggal($tanggal_mulai, $tanggal_selesai)
 *      ->daftarHariLibur(array($tanggal_1, $tanggal_2 ....))
 *      ->jumlahCuti();
 *
 * 4. lengkapnya
 * $this->HitungLibur
 *      ->rentangTanggal($tanggal_mulai, $tanggal_selesai)
 *      ->daftarHariLibur(array($tanggal_1, $tanggal_2 ....))
 *      ->tanpaAkhirPekan()
 *      ->jumlahCuti();
 */

class Jumlah_libur_cuti {

    protected $hariLibur = array();
    protected $tanpaAkhirPekan = false;
    protected $jumlahTanpaLibur = 0;
    protected $jumlahLibur = 0;
    protected $hitungHariTanpaSabtuMinggu = 0;
    protected $tanggal_mulai = "";
    protected $tanggal_selesai = "";

    function Indo_date(){
		$this->CI =& get_instance();
	}

    /**
     * data yang harus dimasukkan untuk menentukan jumlah hari cuti
     * @param  date $tanggal_mulai   tanggal mulai
     * @param  date $tanggal_selesai tanggal selesai
     * @return object
     */
    function rentangTanggal ($tanggal_mulai = "", $tanggal_selesai = "") {
        // jumlah tanpa libur
        $this->jumlahTanpaLibur = ((strtotime($tanggal_selesai)) - (strtotime($tanggal_mulai)))/(60*60*24);

        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;

        return $this;
    }

    /**
     * opsi jika ingin menentukan jumlah hari cuti jika ada libur nasional atau cuti bersama
     * @param  array  $hariLibur daftar hari libur
     * @return object
     */
    function daftarHariLibur ($hariLibur = array()) {
        $this->hariLibur = $hariLibur;

        return $this;
    }

    /**
     * disable sabtu minggu
     * @return object
     */
    function tanpaAkhirPekan () {
        $this->tanpaAkhirPekan = true;

        return $this;
    }

    /**
     * meampilkan jumlah hari tanpa libur
     * @return integer
     */
    function jumlahTanpaLibur () {
        return $this->jumlahTanpaLibur;
    }

    /**
     * hasil akhir menentukan jumlah cuti
     * @return integer
     */
    function jumlahCuti () {
        $explode = explode("-", $this->tanggal_mulai);
        $mulaiHari = $explode[2];
        $mulaiBulan = $explode[1];
        $mulaiTahun = $explode[0];

        $libur = 0;

        $hari = "";

        for ($i = 1; $i <= $this->jumlahTanpaLibur; $i++) {
            $mulaiHari++;
            $buatTanggal = mktime(0, 0, 0, $mulaiBulan, $mulaiHari, $mulaiTahun);
            $tanggal = date("Y-m-d", $buatTanggal);

            // hitung libur jika ada input hari libur
            if (count($this->hariLibur) > 0) {
                if (in_array($tanggal, $this->hariLibur) AND ((date("D", $buatTanggal)) != "Sun" OR (date("D", $buatTanggal)) != "Sat") ) {
                    $libur++;
                }
            }

            // hitung libur untuk sabtu dan minggu
            if ($this->tanpaAkhirPekan) {
                if ( ((date("D", $buatTanggal)) == "Sun" OR (date("D", $buatTanggal)) == "Sat") ) {
                    $libur++;
                }
            }

        }

        return ($this->jumlahTanpaLibur - $libur)+1;
    }

    function reset () {
        $hariLibur = array();
        $tanpaAkhirPekan = false;
        $jumlahTanpaLibur = 0;
        $jumlahLibur = 0;
        $hitungHariTanpaSabtuMinggu = 0;
        $tanggal_mulai = "";
        $tanggal_selesai = "";
    }

    // function __destruct () {
    //     $this->reset();
    // }

}

?>
