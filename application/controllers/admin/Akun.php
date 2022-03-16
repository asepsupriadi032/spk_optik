<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function index(){
        $data['active']     = 'dash';
        $data['judul_1']    = 'Dashboard';
        $data['judul_2']    = 'Selamat Datang';
        $data['page']       = 'v_profile';
        $data['menu'] = $this->Menus->generateMenu();
        $data['breadcumbs'] = array(
            array(
                'nama'=>'Dashboard',
                'icon'=>'fa fa-dashboard',
                'url'=>'admin/dashboard'
            ),
        );

        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }

    function setting(){
        
    }

    public function reset_password()
    {
    	if($_POST){
            // var_dump($this->session->userdata());
            $cur_pass = $this->input->post('cur_pass');
            $new_password = $this->input->post('new_password');
            $re_password = $this->input->post('re_password');

            if($new_password != $re_password){
                redirect(base_url('admin/akun/reset_password'));
            }else{
                $this->db->set('password',$new_password);
                $this->db->where('id',$this->session->userdata('id_admin'));
                $this->db->update('admin');

                redirect(base_url('admin/dashboard'));
            }
    	} else {
    		$data['active']     = 'dash';
	        $data['judul_1']    = 'Admin';
	        $data['judul_2']    = 'Reset Password';
	        $data['page']       = 'v_setting';
	        $data['menu'] = $this->Menus->generateMenu();
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
}