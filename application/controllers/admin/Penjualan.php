<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('Super.php');

class Penjualan extends Super
{
    
    function __construct()
    {
        parent::__construct();
        $this->language       = 'english'; /** Indonesian / english **/
        $this->tema           = "flexigrid"; /** datatables / flexigrid **/
        $this->tabel          = "penjualan";
        $this->active_id_menu = "penjualan";
        $this->nama_view      = "Penjualan";
        $this->status         = true; 
        $this->field_tambah   = array(); 
        $this->field_edit     = array(); 
        $this->field_tampil   = array(); 
        $this->folder_upload  = 'assets/uploads/files';
        $this->add            = true;
        $this->edit           = true;
        $this->delete         = false;
        $this->crud;
    }

    function index(){
            $data = [];
            if($this->crud->getState()=="add")
                redirect(base_url('admin/Penjualan/addPenjualan'));
            if($this->crud->getState()=="edit"){
                $id_penjualan = $this->uri->segment(5);
                redirect(base_url('admin/Penjualan/detailPenjualan/'.$id_penjualan));
            }
            // if()
            //    
            /** Bagian GROCERY CRUD USER**/


            /** Relasi Antar Tabel 
            * @parameter (nama_field_ditabel_ini, tabel_relasi, field_dari_tabel_relasinya)
            **/
             $this->crud->set_relation('id_member','member','nama');
             // $this->crud->set_relation('id_kode','kategori','kode_kacamata');

            /** Upload **/
            // $this->crud->set_field_upload('nama_field_upload',$this->folder_upload);  
            
            /** Ubah Nama yang akan ditampilkan**/
            // $this->crud->display_as('nama','Nama Setelah di Edit')
                $this->crud->display_as('id_member','Nama Member'); 
                $this->crud->display_as('id_kode','Kode Kacamata'); 
                $this->crud->display_as('tanggal_transaksi','Tanggal Transaksi'); 
            
            /** Akhir Bagian GROCERY CRUD Edit Oleh User**/
            $data = array_merge($data,$this->generateBreadcumbs());
            $data = array_merge($data,$this->generateData());
            $this->generate();
            $data['output'] = $this->crud->render();
            $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }

    private function generateBreadcumbs(){
        $data['breadcumbs'] = array(
                array(
                    'nama'=>'Dashboard',
                    'icon'=>'fa fa-dashboard',
                    'url'=>'admin/dashboard'
                ),
                array(
                    'nama'=>'Admin',
                    'icon'=>'fa fa-users',
                    'url'=>'admin/useradmin'
                ),
            );
        return $data;
    }

    public function detailPenjualan($id_penjualan){
        $data = [];
        $data = array_merge($data,$this->generateBreadcumbs());
        $data = array_merge($data,$this->generateData());
        $this->generate();

        $getPenjualan = $this->db->get_where('penjualan',array('id' =>$id_penjualan))->row();

        $id_member = $getPenjualan->id_member;
        $id_penjualan = $getPenjualan->id;

        $data['penjualan'] = $getPenjualan;
        $data['member'] = $this->db->get_where('member',array('id' => $id_member))->row();
        
        $this->db->join('kategori','kategori.id=detail_penjualan.id_kategori','left');
        $data['detailPenjualan'] = $this->db->get_where('detail_penjualan', array('id_penjualan' => $id_penjualan))->result();


        $data['page'] = "detail-penjualan";
        $data['output'] = $this->crud->render();
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }

    public function addPenjualan(){

        $data = [];
        $data = array_merge($data,$this->generateBreadcumbs());
        $data = array_merge($data,$this->generateData());
        $this->generate();

        $data['member'] = $this->db->get('member')->result();
        $data['brg'] = $this->db->get('kategori')->result();


        $data['page'] = "add-penjualan";
        $data['output'] = $this->crud->render();
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }

    public function addMember(){
        $id = $this->input->post('id');
        $row = $this->db->get_where('member', array('id'=>$id))->row();
        $nama = $row->nama;

        $this->session->set_userdata('id_member',$id);
        $this->session->set_userdata('no_member',$row->no_member);
        $this->session->set_userdata('usia',$row->usia);
        $this->session->set_userdata('alamat',$row->alamat);

    
        redirect(base_url('admin/Penjualan/addPenjualan'));
    }

    public function hapusSession(){
        $this->session->unset_userdata('member');
        $this->session->unset_userdata('id_member');
        $this->cart->destroy();

        redirect(base_url('admin/Penjualan/addPenjualan'));
    }

    public function cartMember(){
        $id_member = $this->input->post('id_member');
        $id_kategori = $this->input->post('id_kategori');
        $qty = $this->input->post('qty');

        $row=$this->db->get_where("kategori",array('id'=>$id_kategori))->row();
        $harga=$row->harga;
        //$sisaStok = $row->stok - $qty;//pake stok gak??
        // if ($sisaStok < 0) {
        //     $this->session->set_userdata('alert',true);
        //     $this->session->set_userdata('merk_kacamata',$row->merk_kacamata);
        //     redirect(base_url('admin/Penjualan/addPenjualan'));
        // }

        $data=array(
            'id'=>$row->id,
            'qty'=>$qty,
            'price'=>$harga,
            'name'=>$row->merk_kacamata,
            'kode'=>$row->kode_kacamata,
            'options'=>array('id_member'=>$id_member)
        );
        $this->cart->insert($data);

        // var_dump($this->cart->contents()); die();
        redirect(base_url('admin/Penjualan/addPenjualan'));
    }

    public function hapus_cart_penjualan(){
        $id=$this->input->post('id',true);

        $this->cart->remove($id);
        redirect(base_url('admin/Penjualan/addPenjualan'));
    }

    public function proses_penjualan(){
        if (!$this->input->post())
            redirect('admin/penjualan/addPenjualan');
        $code=time();
        // echo $code;die();
        $data=array('kode_penjualan'=>$code,
                    'id_member'=>$this->input->post('id_penerima'),
                    'tanggal_transaksi'=>date('y-m-d H:i:s'),
                    'total_harga'=>$this->cart->total()
                );

        $this->db->insert('penjualan',$data);
        $id_penjualan=$this->db->insert_id();

        // var_dump($id_penjualan); die();
        foreach ($this->cart->contents() as $items){
            // print_r($items);die();

            $this->db->insert('detail_penjualan',array(
                'id_penjualan'=>$id_penjualan,
                'id_kategori'=>$items['id'],
                'qty'=>$items['qty'],
                'harga'=>$items['price'],
                'sub_total'=>$items['subtotal']
            ));



                
        }
        $this->session->unset_userdata('id_member');
        $this->session->unset_userdata('no_member');
        $this->session->unset_userdata('usia');
        $this->session->unset_userdata('alamat');
        $this->cart->destroy();
        redirect(base_url('admin/Penjualan'));
    }
}