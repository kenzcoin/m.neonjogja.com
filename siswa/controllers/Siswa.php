<?php

/**
 *
 */
class Siswa extends MX_Controller {

     function get_status_login(){
        $log_in = $this->session->userdata('loggedin');
        return $log_in;        
    }

    function get_hak_akses(){
        $hak_akses = $this->session->userdata('HAKAKSES');        
        return $hak_akses;        
    }

    public function __construct() {
        parent::__construct();
        $this->load->model('msiswa');
        $this->load->model('register/mregister');
         $this->load->model('cabang/mcabang');
        $this->load->helper('session');
        $this->load->library('parser');
        $this->load->model('tryout/mtryout');
    }

    public function index() {
        $data['siswa'] = $this->msiswa->get_datsiswa();
        $this->load->view('templating/t-header');
        $this->load->view('templating/t-navbarUser');
        $this->load->view('t-profile-siswa', $data);
        $this->load->view('templating/t-footer');
    }

    // FUNGSI VIEW PROFILE SISWA
    public function profilesetting() {
        if ($this->session->userdata('NAMASISWA')) {
         $data = array(

            'judul_halaman' => 'Neon - Pengaturan Akun',

            'judul_header' =>'Pengaturan Akun',

            'judul_header2' =>'Pengaturan Akun'
        );

        $data['files'] = array( 

            APPPATH.'modules/templating/views/anggi/v-sidebar.php',
            APPPATH.'modules/siswa/views/mobile/v-pengaturan-profile.php',
            APPPATH.'modules/templating/views/anggi/v-footer.php',

        );
        $penggunaID = $this->session->userdata['id'];
        
        $data['siswa'] = $this->load->msiswa->get_siswapoto($penggunaID);
        $data['sis'] = $this->msiswa->get_datsiswa();
        $this->parser->parse( 'templating/layouts/index', $data );
        } else {
            redirect('login');
        }
    }

    // FUNGSI UNTUK UBAH PROFILE SISWA
    public function ubahprofilesiswa() {
        //load library n helper
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        //syarat pengisian form perubahan profile
        $this->form_validation->set_rules('namadepan', 'Nama Depan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('nokontak', 'No Kontak', 'required');
        ;

        //pesan error atau pesan kesalahan pengisian form
        $this->form_validation->set_message('is_unique', '*Nama Pengguna sudah terpakai');
        $this->form_validation->set_message('max_length', '*Nama Pengguna maksimal 12 karakter!');
        $this->form_validation->set_message('min_length', '*Nama Pengguna minimal 5 karakter!');
        $this->form_validation->set_message('required', '*tidak boleh kosong!');

        if ($this->form_validation->run() == FALSE) {
            $this->profilesetting();
        } else {
            $namaDepan = htmlspecialchars($this->input->post('namadepan'));
            $namaBelakang = htmlspecialchars($this->input->post('namabelakang'));
            $alamat = htmlspecialchars($this->input->post('alamat'));
            $noKontak = htmlspecialchars($this->input->post('nokontak'));
            $biografi = htmlspecialchars($this->input->post('biografi'));
            $namaSekolah = htmlspecialchars($this->input->post('namasekolah'));
            $alamatSekolah = htmlspecialchars($this->input->post('alamatsekolah'));


            $data_post = array(
                'namaDepan' => $namaDepan,
                'namaBelakang' => $namaBelakang,
                'alamat' => $alamat,
                'noKontak' => $noKontak,
                'biografi' => $biografi,
                'namaSekolah' => $namaSekolah,
                'alamatSekolah' => $alamatSekolah,
            );

            $this->session->set_flashdata('updsiswa', 'Data profilmu telah berubah');
            $this->msiswa->update_siswa($data_post);
        }
    }

    // FUNGSI VIEW UBAH PHOTO
    public function photosetting() {
        if ($this->session->userdata('NAMASISWA')) {
             $data = array(

                'judul_halaman' => 'Neon - Pengaturan Akun',

                'judul_header' =>'Pengaturan Akun',

                'judul_header2' =>'Pengaturan Akun'



            );

            $data['files'] = array( 

                APPPATH.'modules/templating/views/anggi/v-sidebar.php',
                APPPATH.'modules/siswa/views/mobile/vm-f-ubahphoto.php',
                APPPATH.'modules/templating/views/anggi/v-footer.php',
            );

            $penggunaID = $this->session->userdata['id'];
            
            $data['siswa'] = $this->load->msiswa->get_siswapoto($penggunaID);
            $data['sis'] = $this->msiswa->get_datsiswa();
            $this->parser->parse( 'templating/anggi/index', $data );

        } else {
            redirect('login');
        }
    }

    // FUNGSI UNTUK UPLOAD PHOTO
     public function upload($oldphoto) {
        unlink(FCPATH . "./assetsnew/image/photo/siswa/" . $oldphoto);
        $config['upload_path'] = './assetsnew/image/photo/siswa';
        $config['allowed_types'] = 'jpeg|gif|jpg|png|mkv';
        $config['max_size'] = 580;
        $config['max_width'] = 2500;
        $config['max_height'] = 1500;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) {

            $data['error'] = array('error' => $this->upload->display_errors());

        } else {
            $file_data = $this->upload->data();
            $photo = $file_data['file_name'];
            $this->session->set_flashdata('updsiswa', 'Foto profilmu telah berubah');
            $this->msiswa->update_photo($photo);
            $this->session->set_flashdata('updsiswa','<div class="alert alert-warning fade in">

                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                    <span class="semibold">Note :</span>&nbsp;&nbsp;Photo profilmu telah berubah.

                                </div>');
            redirect('siswa/photosetting');
        }
    }

    // VIEW UBAH EMAIL
    public function emailsetting() {
        if ($this->session->userdata('NAMASISWA')) {
             $data = array(
                'judul_halaman' => 'Neon - Pengaturan Akun',
                'judul_header' =>'Pengaturan Akun',
                'judul_header2' =>'Pengaturan Akun'
            );

            $data['files'] = array( 

                APPPATH.'modules/templating/views/anggi/v-sidebar.php',
                APPPATH.'modules/siswa/views/mobile/vm-f-ubahemail.php',
                APPPATH.'modules/templating/views/anggi/v-footer.php',

            );

            $penggunaID = $this->session->userdata['id'];
            
            $data['siswa'] = $this->load->msiswa->get_siswapoto($penggunaID);
            $data['sis'] = $this->msiswa->get_datsiswa();
            $this->parser->parse( 'templating/anggi/index', $data );
        } else {
            redirect('login');
        }
    }

    // FUNGSI UNTUK UPDATE EMAIL
    public function ubahemailsiswa() {
        if ($this->session->userdata('NAMASISWA')) {
            //load library n helper
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');


            //load library n helper
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            //syarat pengisian form perubahan nama pengguna dan email

            $this->form_validation->set_rules('email', 'email', 'required|is_unique[tb_pengguna.eMail]');

            //pesan error atau pesan kesalahan pengisian form
            $this->form_validation->set_message('is_unique', '*Email sudah terpakai');
            $this->form_validation->set_message('required', '*tidak boleh kosong!');


            if ($this->form_validation->run() == FALSE) {
                 $this->emailsetting();
           
            } else {
                $email = htmlspecialchars($this->input->post('email'));

                $data_post = array(
                    'eMail' => $email,
                );
                $this->msiswa->update_email($data_post);
                $this->session->set_flashdata('updsiswa','<div class="alert alert-warning fade in">

                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                        <span class="semibold">Note :</span>&nbsp;&nbsp;Emailmu telah berubah.

                                    </div>');
                redirect('siswa/emailsetting');
            }
        } else {
            redirect('login');
        }
    }

    // VIEW UBAH PASSWORD
    public function passwordsetting() {
         $data = array(

            'judul_halaman' => 'Neon - Pengaturan Akun',

            'judul_header' =>'Pengaturan Akun',

            'judul_header2' =>'Pengaturan Akun'



        );

        $data['files'] = array( 

            APPPATH.'modules/templating/views/anggi/v-sidebar.php',
            APPPATH.'modules/siswa/views/mobile/vm-f-ubahpassword.php',
            APPPATH.'modules/templating/views/anggi/v-footer.php',
        );

        $penggunaID = $this->session->userdata['id'];
            
        $data['siswa'] = $this->load->msiswa->get_siswapoto($penggunaID);
        $data['sis'] = $this->msiswa->get_datsiswa();
        $this->parser->parse( 'templating/anggi/index', $data );
    }

    // fungsi update password
    public function ubahkatasandi() {

        //load library n helper
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');


        //syarat pengisian form perubahan pasword
        $this->form_validation->set_rules('sandilama', 'Kata Sandi Lama', 'required');
        $this->form_validation->set_rules('newpass', 'Kata Sandi Baru', 'required|matches[verifypass]');
        $this->form_validation->set_rules('verifypass', 'Password Confirmation', 'required');

        //pesan error atau pesan kesalahan pengisian form
        $this->form_validation->set_message('required', '*tidak boleh kosong!');
        $this->form_validation->set_message('matches', '*Kata Sandi tidak sama!');


        if ($this->form_validation->run() == FALSE) {

            $this->profilesetting();
        } else {
            $kataSandi = htmlspecialchars(md5($this->input->post('newpass')));
            $inputSandi = htmlspecialchars(md5($this->input->post('sandilama')));
            $data_post = array('kataSandi' => $kataSandi,);
            $data['pengguna'] = $this->msiswa->get_password()[0];
            $kataSandi = $data['pengguna']['kataSandi'];
            if ($kataSandi == $inputSandi) {
                $this->msiswa->update_katasandi($data_post);
                $this->session->set_flashdata('updsiswa','<div class="alert alert-warning fade in">

                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                        <span class="semibold">Note :</span>&nbsp;&nbsp;Password telah berubah.

                                    </div>');
                redirect(site_url('siswa/passwordsetting'));
            } else {
                $this->session->set_flashdata('updsiswa', 'Password gagal  dirubah, password lama salah');
                redirect(site_url('siswa/passwordsetting'));
            }
        }
    }

   
    ##menampilkan daftar siswa ajax

    public function ajax_daftar_siswa() {
        $list = $this->msiswa->get_all_siswa();

        $data = array();
        $no = 0;
        //mengambil nilai list
        $baseurl = base_url();
        foreach ($list as $list_siswa) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list_siswa['idsiswa'];
            $row[] = $list_siswa['namaDepan'] . " " . $list_siswa['namaBelakang'];
            $row[] = $list_siswa['namaPengguna'];

            $row[] = $list_siswa['namaSekolah'];
            $row[] = '<a href=""  title="Mail To">' . $list_siswa['eMail'] . '</a> <i class="ico-mail-send"></i>';
            $row[] = '<a href="' . base_url('index.php/siswa/reportSiswa/' . $list_siswa['penggunaID']) . '" "> Lihat detail</a></i>';

            $row[] = '<a class="btn btn-sm btn-warning"  title="Edit" href="' . base_url('index.php/siswa/updateSiswa/' . $list_siswa['idsiswa'] . '/' . $list_siswa['penggunaID']) . '" "><i class="ico-edit"></i></a> 

        <a class="btn btn-sm btn-danger"  title="Hapus" onclick="dropSiswa(' . "" . $list_siswa['idsiswa'] . "," . $list_siswa['penggunaID'] . ')"><i class="ico-remove"></i></a>';

            $data[] = $row;
        }

        $output = array(
            "data" => $data,
        );
        echo json_encode($output);
    }

    ##menampilkan siswa

    public function daftar() {
        $data['judul_halaman'] = "Pengelolaan Data Siswa";
        $data['files'] = array(
            APPPATH . 'modules/siswa/views/v-daftar-siswa.php',
        );
        // jika admin
        $this->parser->parse('admin/v-index-admin', $data);
    }

    public function daftarsiswa() {
        $data['judul_halaman'] = "Pengelolaan Data Siswa";
        $data['files'] = array(
            APPPATH . 'modules/siswa/views/v-form-siswa.php',
        );

        $data['mataPelajaran'] = $this->mregister->get_matapelajaran();
        $data['cabang'] = $this->mcabang->get_all_cabang();
        
        $this->parser->parse('admin/v-index-admin', $data);
    }

//function untuk menyimpan data pendaftaran user siswa ke database
    public function savesiswa() {
//load library n helper
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

//syarat pengisian form regitrasi siswa
        $this->form_validation->set_rules('namapengguna', 'Nama Pengguna', 'trim|required|min_length[5]|max_length[12]|is_unique[tb_pengguna.namaPengguna]');
        $this->form_validation->set_rules('namadepan', 'Nama Depan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('nokontak', 'No Kontak', 'required');
        $this->form_validation->set_rules('katasandi', 'Kata Sandi', 'required|matches[passconf]|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tb_pengguna.email]');


//pesan error atau pesan kesalahan pengisian form registrasi siswa
        $this->form_validation->set_message('is_unique', '*Nama Pengguna atau email sudah terpakai');
        $this->form_validation->set_message('max_length', '*Nama Pengguna maksimal 12 karakter!');
        $this->form_validation->set_message('min_length', '*Inputan minimal 6 karakter!');
        $this->form_validation->set_message('required', '*tidak boleh kosong!');
        $this->form_validation->set_message('matches', '*Kata Sandi tidak sama!');
        $this->form_validation->set_message('valid_email', '*silahkan masukan alamat email anda dengan benar');

//pengecekan pengisian form regitrasi siswa
        if ($this->form_validation->run() == FALSE) {
//jika tidak memenuhi syarat akan menampilkan pesan error/kesalahan di halaman regitrasi siswa
            $this->daftarsiswa();
        } else {

//data siswa
            $namaDepan = htmlspecialchars($this->input->post('namadepan'));
            $namaBelakang = htmlspecialchars($this->input->post('namabelakang'));
            $alamat = htmlspecialchars($this->input->post('alamat'));
            $noKontak = htmlspecialchars($this->input->post('nokontak'));


            $tingkatID = htmlspecialchars($this->input->post('tingkatID'));
            $namaSekolah = htmlspecialchars($this->input->post('namasekolah'));
            $alamatSekolah = htmlspecialchars($this->input->post('alamatsekolah'));

//data akun
            $namaPengguna = htmlspecialchars($this->input->post('namapengguna'));
            $kataSandi = htmlspecialchars(md5($this->input->post('katasandi')));
            $email = htmlspecialchars($this->input->post('email'));
            $hakAkses = 'siswa';

//data array akun
            $data_akun = array(
                'namaPengguna' => $namaPengguna,
                'kataSandi' => $kataSandi,
                'eMail' => $email,
                'hakAkses' => $hakAkses,
            );


//melempar data guru ke function insert_pengguna di kelas model
            $data['mregister'] = $this->mregister->insert_pengguna($data_akun);
//untuk mengambil nilai id pengguna untuk di jadikan FK pada tabel siswa
            $data['tb_pengguna'] = $this->mregister->get_idPengguna($namaPengguna)[0];

            $penggunaID = $data['tb_pengguna']['id'];

//session buat id
            $id_arr = array('id' => $penggunaID);
//            $this->session->set_userdata($id_arr);
//data array siswa
            $data_siswa = array(
                'namaDepan' => $namaDepan,
                'namaBelakang' => $namaBelakang,
                'alamat' => $alamat,
                'noKontak' => $noKontak,
                'namaSekolah' => $namaSekolah,
                'alamatSekolah' => $alamatSekolah,
                'penggunaID' => $penggunaID,
                'tingkatID' => $tingkatID,
            );
//data unutk session siswa
//            $sess_array = array(
//                'id' => $penggunaID,
//                'USERNAME' => $namaPengguna,
//                'HAKAKSES' => $hakAkses,
//                'eMail' => $email,
//                'tingkatID' => $tingkatID
//            );
//melempar data guru ke function insert_guru di kelas model
            $data['mregister'] = $this->mregister->insert_siswabyadmin($data_siswa, $email, $namaPengguna);
//            redirect(site_url('register/verifikasi'));

            redirect(base_url('index.php/siswa/daftarsiswa'));
        }
    }

    public function deleteSiswa() {
        $idsiswa = $this->input->post('idsiswa');
        $idpengguna = $this->input->post('idpengguna');
        $this->msiswa->hapus_siswa($idsiswa, $idpengguna);
    }

    //tgl 30 Oktober
    function updateSiswa($idsiswa, $idpengguna) {
        if ($idsiswa == null || $idpengguna == 0) {
            echo 'kosong';
        } else {
             $data['mataPelajaran'] = $this->mregister->get_matapelajaran();
        $data['cabang'] = $this->mcabang->get_all_cabang();
            $idsiswa = $idsiswa;
            $idpengguna = $idpengguna;

            $data['siswa'] = $this->msiswa->get_siswa_byid($idsiswa, $idpengguna);

//            var_dump($data);

            $data['judul_halaman'] = "Rubah Data Siswa";
            $data['files'] = array(
                APPPATH . 'modules/siswa/views/v-update-siswa.php',
            );
            // jika admin
            $this->parser->parse('admin/v-index-admin', $data);
        }
    }

    //tgl 30 Oktober
    function reportSiswa($idpengguna) {
        if ($idpengguna == 0) {
            // echo 'kosong';
        } else {
            $idpengguna = $idpengguna;
            $data['reportla'] = $this->msiswa->get_reportlatihan_siswa($idpengguna);
            $data['reportto'] = $this->msiswa->get_reporttryout_siswa($idpengguna);
//            var_dump($data);

            $data['judul_halaman'] = "Report Siswa";
            $data['files'] = array(
                APPPATH . 'modules/siswa/views/v-report-siswa.php',
            );
//            // jika admin
            $this->parser->parse('admin/v-index-admin', $data);
        }
    }

    //tgl 30 Oktober
    function reportto() {
        // var_dump($this->session->userdata());
          $idto = $this->uri->segment(3);
          // echo $idto;
        // echo $this->session->userdata['id'];
        if (empty($idto)) {
            // echo 'tidak ada tryout';
            // redirect(baseurl('siswa/'))
        } else {
            $idpengguna =  $this->uri->segment(4);
            // $idto = $this->uri->segmen(3);
            $data['reportpaket'] = $this->msiswa->get_reportpaket_to($idpengguna,$idto);
            $data['ratarata'] = $this->msiswa->ratarata_to($idpengguna,$idto);

            $data['judul_halaman'] = "Report Siswa";
            $data['files'] = array(
                APPPATH . 'modules/siswa/views/v-report-paket.php',
            );
//            // jika admin
            $this->parser->parse('admin/v-index-admin', $data);
        }
    }

    ##menampilkan daftar siswa ajax

    public function ajax_daftar_latihan() {
        $list = $this->msiswa->get_all_siswa();

        $data = array();
        $no = 0;
        //mengambil nilai list
        $baseurl = base_url();
        foreach ($list as $list_siswa) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $list_siswa['idsiswa'];
            $row[] = $list_siswa['namaDepan'] . " " . $list_siswa['namaBelakang'];
            $row[] = $list_siswa['namaPengguna'];

            $row[] = $list_siswa['namaSekolah'];
            $row[] = '<a href=""  title="Mail To">' . $list_siswa['eMail'] . '</a> <i class="ico-mail-send"></i>';
            $row[] = '<a href="' . base_url('index.php/siswa/reportSiswa/' . $list_siswa['penggunaID']) . '" "> Lihat detail</a></i>';

            $row[] = '<a class="btn btn-sm btn-warning"  title="Edit" href="' . base_url('index.php/siswa/updateSiswa/' . $list_siswa['idsiswa'] . '/' . $list_siswa['penggunaID']) . '" "><i class="ico-edit"></i></a> 

        <a class="btn btn-sm btn-danger"  title="Hapus" onclick="dropSiswa(' . "" . $list_siswa['idsiswa'] . "," . $list_siswa['penggunaID'] . ')"><i class="ico-remove"></i></a>';

            $data[] = $row;
        }

        $output = array(
            "data" => $data,
        );
        echo json_encode($output);
    }


        public function test() {
         $data = array(

            'judul_halaman' => 'Neon - Welcome',
            'judul_header' =>'Welcome',
            'judul_header2' =>'Welcome'
        );

        $data['files'] = array( 
            APPPATH.'modules/homepage/views/v-header-login.php',
            APPPATH.'modules/siswa/views/headersiswa.php',
            APPPATH.'modules/siswa/views/v-dashboard.php',
            APPPATH.'modules/testimoni/views/v-footer.php',
        );

        $data['siswa'] = $this->msiswa->get_datsiswa()[0];
        $data['token'] = $this->session->userdata('token');
        var_dump($data['token']);
        $this->parser->parse( 'templating/index', $data );
    }


    function get_tryout_for_select(){
   if ($this->get_status_login()){
    $datas = $this->mtryout->get_tryout_by_pengguna();
    echo json_encode($datas);
    }else{
    redirect('login');
}

}

public function ajax_getsiswa()
    {
      $siswaID=$this->session->userdata['id'];
      $arrSiswa=$this->msiswa->get_siswa_id($siswaID);
      echo json_encode($arrSiswa);
    }
    
    
}

?>
