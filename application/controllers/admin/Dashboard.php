<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct()
    {
        parent::__construct();
         if(!$this->session->userdata('login_admin')){
            redirect('admin/login');
        }
    }

    function index(){
        $data['active']     = 'dash';
        $data['judul_1']    = 'Admin CSL - '.$this->session->userdata('nama');
        $data['judul_2']    = 'Selamat Datang';
        $data['page']       = 'v_dashboard';
        $data['menu']       = $this->Menus->generateMenu();
        $data['breadcumbs'] = array(
            array(
                'nama'=>'Dashboard',
                'icon'=>'fa fa-dashboard',
                'url'=>'admin/dashboard'
            ),
        );

        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }
}