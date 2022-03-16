<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('Super.php');

class Kalkulasi extends Super
{
    
    function __construct()
    {
        parent::__construct();
        $this->language       = 'english'; /** Indonesian / english **/
        $this->tema           = "flexigrid"; /** datatables / flexigrid **/
        $this->tabel          = "kalkulasi";
        $this->active_id_menu = "kalkulasi";
        $this->nama_view      = "kalkulasi";
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
                redirect(base_url('admin/Kalkulasi/addKalkulasi'));
            if($this->crud->getState()=="edit"){
                $id = $this->uri->segment(5);
                redirect(base_url('admin/Kalkulasi/editKalkulasi/'.$id));
            }if($this->crud->getState()=="read"){
                $id = $this->uri->segment(5);
                redirect(base_url('admin/Kalkulasi/hasilKalkulasi/'.$id));
            }
            /** Bagian GROCERY CRUD USER**/


            /** Relasi Antar Tabel 
            * @parameter (nama_field_ditabel_ini, tabel_relasi, field_dari_tabel_relasinya)
            **/
            // $this->crud->set_relation('parent_menu','tjm_menu','nama_menu');

            /** Upload **/
            // $this->crud->set_field_upload('nama_field_upload',$this->folder_upload);  
            
            /** Ubah Nama yang akan ditampilkan**/
            // $this->crud->display_as('nama','Nama Setelah di Edit')
            //     ->display_as('email','Email Setelah di Edit'); 
            
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

    public function addKalkulasi(){

        $data = [];
        $data = array_merge($data,$this->generateBreadcumbs());
        $data = array_merge($data,$this->generateData());
        $this->generate();

        $data['page'] = "add-kalkulasi";
        $data['output'] = $this->crud->render();
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }

    public function insertKalkulasi(){
        $judul = $this->input->post('judul');
        $tanggal = $this->input->post('tanggal');
        $periode_awal = $this->input->post('periode_awal');
        $periode_akhir = $this->input->post('periode_akhir');
         
        //convert
        $timeStart = strtotime($periode_awal);
        $timeEnd = strtotime($periode_akhir);
         
        // Menambah bulan ini + semua bulan pada tahun sebelumnya
        $numBulan = 1 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
         
        // hitung selisih bulan
        $numBulan += date("m",$timeEnd)-date("m",$timeStart);
         
        if($numBulan != 6){
            redirect(base_url('admin/Kalkulasi/addKalkulasi/'));
        }

        // $this->db->where("DATE_FORMAT(periode_awal,'%Y-%m')", $periode_awal);
        $this->db->where('periode_awal',$periode_awal);
        $this->db->where('periode_akhir',$periode_akhir);
        $getKalkulasi = $this->db->get('kalkulasi')->num_rows();

        if($getKalkulasi >0){
            redirect(base_url('admin/Kalkulasi/addKalkulasi'));
        }
        
        $this->db->set('judul',$judul);
        $this->db->set('tanggal_kalkulasi',$tanggal);
        $this->db->set('periode_awal',$periode_awal);
        $this->db->set('periode_akhir',$periode_akhir);
        $this->db->set('status','Belum diproses');
        $this->db->insert('kalkulasi');

        redirect(base_url('admin/Kalkulasi'));
    }

    public function editKalkulasi($id){

        $data = [];
        $data = array_merge($data,$this->generateBreadcumbs());
        $data = array_merge($data,$this->generateData());
        $this->generate();

        $data['periode'] = $this->db->get_where('kalkulasi',array('id'=>$id))->row();

        $data['page'] = "edit-kalkulasi";
        $data['output'] = $this->crud->render();
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }


    public function prosesKriteria(){
        $id = $this->input->post('id');
        $getPeriode = $this->db->get_where('kalkulasi',array('id'=>$id))->row();


        if($getPeriode->status == 'Sudah diproses'){
            redirect(base_url('admin/KmeansHasil/index'."/".$id));
        }

        $periode_awal = $getPeriode->periode_awal;
        $periode_akhir = $getPeriode->periode_akhir;

        $this->db->select('id_member');
        $this->db->where('tanggal_transaksi >=',$periode_awal);
        $this->db->where('tanggal_transaksi <=',$periode_akhir);
        $this->db->group_by('id_member');
        $getPenjualan = $this->db->get('penjualan')->result();

        $totalM =[];
        $totalR = [];
        $totalF = [];
        $member = [];
        $no = 0;
        $data = [];
        foreach ($getPenjualan as $rowPenjualan) {
            $id_member =  $rowPenjualan->id_member;
            $member[$no] = $id_member;

            $this->db->where('id_member',$id_member);
            $this->db->where('tanggal_transaksi >=',$periode_awal);
            $this->db->where('tanggal_transaksi <=',$periode_akhir);
            $memberRow = $this->db->get('penjualan');
            $getMember = $memberRow->result();

            //total nilai M
            $total = 0;
            foreach ($getMember as $rowMember) {
                $total = $total + $rowMember->total_harga;
            }

            $totalM[$no] = $this->bobot($total,'M');
            //batas mencari total M

            //mencari F
            $rowMember = $memberRow->num_rows();
            $totalF[$no] = $this->bobot($rowMember,'F');
           //Batas mencari F

            //mencari R
            $this->db->limit(1);
            $this->db->order_by('tanggal_transaksi','DESC');
            $this->db->where('id_member',$id_member);
            $this->db->where('tanggal_transaksi >=',$periode_awal);
            $this->db->where('tanggal_transaksi <=',$periode_akhir);
            $getR = $this->db->get('penjualan')->row();
            $tanggal[$no] = $getR->tanggal_transaksi;

            $tgl_awal = new DateTime($tanggal[$no]);
            $dateNow = new DateTime($periode_akhir);
            $lama[$no] = $dateNow->diff($tgl_awal)->days +1;

            $totalR[$no] = $this->bobot($lama[$no],'R');
            //Batas mencari R

            //tampil
            $c[$no] = array(
                    'id_member'=>$member[$no],
                    'r'=>$totalR[$no],
                    'f'=>$totalF[$no],
                    'm'=>$totalM[$no]
                    );

            $data[$no] = 'id_member: '.$member[$no].' RFM = '.$totalR[$no].''.$totalF[$no].''.$totalM[$no].'<p>';
            // $data[$no] = 'id_member: '.$member[$no].' R = '.$lama[$no].':'.$totalR[$no].' F: '.$totalF[$no].' M: '.$totalM[$no].'<p>';

            $this->db->set('id_proses',$id);
            $this->db->set('id_member',$member[$no]);
            $this->db->set('nilai_r',$totalR[$no]);
            $this->db->set('nilai_f',$totalF[$no]);
            $this->db->set('nilai_m',$totalM[$no]);
            $this->db->insert('data_bobot');

            $no++;
            // die();
        }

        shuffle($c);
        $c1 = $c[1];
        $c2 = $c[2];

        $this->db->set('id_proses',$id);
        $this->db->set('tipe','C1');
        $this->db->set('r','4');
        $this->db->set('f','2');
        $this->db->set('m','3');
        $this->db->insert('centroid_data');

        $this->db->set('id_proses',$id);
        $this->db->set('tipe','C2');
        $this->db->set('r','2');
        $this->db->set('f','1');
        $this->db->set('m','1');
        $this->db->insert('centroid_data');
        /*$this->db->set('id_proses',$id_proses);
       $this->db->set('tipe','C1');
       $this->db->set('r',$c[1]['r']);
       $this->db->set('f',$c[1]['f']);
       $this->db->set('m',$c[1]['m']);
       $this->db->insert('centroid_data');

       $this->db->set('id_proses',$id_proses);
       $this->db->set('tipe','C2');
       $this->db->set('r',$c[2]['r']);
       $this->db->set('f',$c[2]['f']);
       $this->db->set('m',$c[2]['m']);
       $this->db->insert('centroid_data');*/

        redirect(base_url('admin/Kalkulasi/prosesHitung/'.$id));
        // var_dump($data);
        // die();
    }

    public function prosesHitung($id){

        //mengambil c1
        $C1 = $this->c1($id);
        //mengambil c2
        $C2 = $this->c2($id);

        $i=0;
        $totalC1 = 0;
        $totalR1 = 0;
        $totalF1 = 0;
        $totalM1 = 0;
        $totalC2 = 0;
        $totalR2 = 0;
        $totalF2 = 0;
        $totalM2 = 0;
        
        $getDataMember = $this->db->get_where('data_bobot',array('id_proses'=>$id))->result();
        foreach ($getDataMember as $key) {
            $hasilC1[$i] = $this->hitungC1($C1->r, $key->nilai_r, $C1->f, $key->nilai_f, $C1->m, $key->nilai_m);

            $hasilC2[$i] = $this->hitungC2($C2->r, $key->nilai_r, $C2->f, $key->nilai_f, $C2->m, $key->nilai_m);

            if($hasilC1[$i] < $hasilC2[$i]){
                $hasil = 1;
                $totalC1 = $totalC1 + 1;
                $totalR1 = $totalR1 + $key->nilai_r;
                $totalF1 = $totalF1 + $key->nilai_f;
                $totalM1 = $totalM1 + $key->nilai_m;
              }else{
                $hasil = 0;
                $totalC2 = $totalC2 + 1;
                $totalR2 = $totalR2 + $key->nilai_r;
                $totalF2 = $totalF2 + $key->nilai_f;
                $totalM2 = $totalM2 + $key->nilai_m;
              }
              $hasilIterasi[$i] = array(
                                      'id_member'=>$key->id_member,
                                      'c1'=>$hasilC1[$i],
                                      'c2'=>$hasilC2[$i],
                                      'hasil'=>$hasil
                                      );

              $this->db->set('id_member',$key->id_member);
              $this->db->set('id_proses',$id);
              $this->db->set('c11',$hasilC1[$i]);
              $this->db->set('c21',$hasilC2[$i]);
              $this->db->set('hasil1',$hasil);
              $this->db->insert('hasil_rfm');

            $i++;
        }


        $newC1 = array('r'=>round($totalR1/$totalC1,2), 'f'=>round($totalF1/$totalC1,2), 'm'=>round($totalM1/$totalC1,2));
        $newC2 = array('r'=>round($totalR2/$totalC2,2), 'f'=>round($totalF2/$totalC2,2), 'm'=>round($totalM2/$totalC2,2));

        $newIterasi = $this->iterasi2($id, $newC1, $newC2);

         // if($newIterasi==$hasilIterasi){
            $this->db->where('id_proses',$id);
            $this->db->where('tipe','C1');
            $this->db->set('r2',$newC1['r']);
            $this->db->set('f2',$newC1['f']);
            $this->db->set('m2',$newC1['m']);
            $this->db->update('centroid_data');

            $this->db->where('id_proses',$id);
            $this->db->where('tipe','C2');
            $this->db->set('r2',$newC2['r']);
            $this->db->set('f2',$newC2['f']);
            $this->db->set('m2',$newC2['m']);
            $this->db->update('centroid_data');

        foreach ($newIterasi as $key) {
              $this->db->where('id_member',$key['id_member']);
              $this->db->where('id_proses',$id);
              $this->db->set('c12',$key['c1']);
              $this->db->set('c22',$key['c2']);
              $this->db->set('c22',$key['c2']);
              $this->db->set('hasil2',$key['hasil']);
              $this->db->update('hasil_rfm');
            }
        $this->db->set('status','Sudah diproses');
            $this->db->where('id',$id);
            $this->db->update('kalkulasi');

        // var_dump($hasilIterasi);
        // die();
        redirect(base_url('admin/KmeansHasil/index'."/".$id));
    }

    public function bobot($bobotTeam,$code){

        $getBobotM = $this->db->query('SELECT * FROM kriteria WHERE batas_awal <= "'.$bobotTeam.'" AND "'.$bobotTeam.'" <= batas_akhir AND code ="'.$code.'"')->row();

        if($getBobotM){
            $hasil = $getBobotM->bobot;

            return $hasil;        
        }
        
    }

    public function iterasi2($id_proses, $C1, $C2){
          
          // var_dump($C1['r']); die();
          $i=0;
          $totalC1 = 0;
          $totalR1 = 0;
          $totalF1 = 0;
          $totalM1 = 0;
          $totalC2 = 0;
          $totalR2 = 0;
          $totalF2 = 0;
          $totalM2 = 0;
          
          $getDataRFM = $this->db->get_where('data_bobot', array('id_proses'=>$id_proses))->result();
          foreach ($getDataRFM as $key) {
              $hasilC1[$i] = $this->hitungC1($C1['r'], $key->nilai_r, $C1['f'], $key->nilai_f, $C1['m'], $key->nilai_m);

              $hasilC2[$i] = $this->hitungC2($C2['r'], $key->nilai_r, $C2['f'], $key->nilai_f, $C2['m'], $key->nilai_m);

              if($hasilC1[$i] < $hasilC2[$i]){
                $hasil = 1;
                $totalC1 = $totalC1 + 1;
                $totalR1 = $totalR1 + $key->nilai_r;
                $totalF1 = $totalF1 + $key->nilai_f;
                $totalM1 = $totalM1 + $key->nilai_m;
              }else{
                $hasil = 0;
                $totalC2 = $totalC2 + 1;
                $totalR2 = $totalR2 + $key->nilai_r;
                $totalF2 = $totalF2 + $key->nilai_f;
                $totalM2 = $totalM2 + $key->nilai_m;
              }

              $hasilIterasi[$i] = array(
                                      'id_member'=>$key->id_member,
                                      'c1'=>$hasilC1[$i],
                                      'c2'=>$hasilC2[$i],
                                      'hasil'=>$hasil
                                      );
            $i++;
          }

          return $hasilIterasi;
    }

    public function c1($id_proses){
      $this->db->where('id_proses',$id_proses);
      $this->db->where('tipe',"C1");
      return $this->db->get('centroid_data')->row();
    }

    public function c2($id_proses){
      $this->db->where('id_proses',$id_proses);
      $this->db->where('tipe',"C2");
      return $this->db->get('centroid_data')->row();
    }

    public function hitungC1($bobot_r, $nilai_r, $bobot_f, $nilai_f, $bobot_m, $nilai_m){
      return round(sqrt((($bobot_r - $nilai_r)*($bobot_r - $nilai_r)) + (($bobot_f - $nilai_f)*($bobot_f - $nilai_f)) + (($bobot_m - $nilai_m)*($bobot_m - $nilai_m))),2);
    }

    public function hitungC2($bobot_r, $nilai_r, $bobot_f, $nilai_f, $bobot_m, $nilai_m){
      return round(sqrt((($bobot_r - $nilai_r)*($bobot_r - $nilai_r)) + (($bobot_f - $nilai_f)*($bobot_f - $nilai_f)) + (($bobot_m - $nilai_m)*($bobot_m - $nilai_m))),2);
    }

    public function hasilKalkulasi($id_proses){
        $data = [];
        $data = array_merge($data,$this->generateBreadcumbs());
        $data = array_merge($data,$this->generateData());
        $this->generate();

        $this->db->where('hasil2',1);
        $this->db->where('id_proses',$id_proses);
        $this->db->join('member','member.id=hasil_rfm.id_member','left');
        $data['totalLoyal'] = $this->db->get('hasil_rfm')->num_rows();

        $this->db->where('hasil2',0);
        $this->db->where('id_proses',$id_proses);
        $this->db->join('member','member.id=hasil_rfm.id_member','left');
        $data['totalTidakLoyal'] = $this->db->get('hasil_rfm')->num_rows();

        $this->db->where('hasil2',1);
        $this->db->order_by('c21','ASC');
        $this->db->where('id_proses',$id_proses);
        $this->db->join('member','member.id=hasil_rfm.id_member','left');
        $data['dataTeamLoyal'] = $this->db->get('hasil_rfm')->result();

        $this->db->where('hasil2',0);
        $this->db->order_by('c22','ASC');
        $this->db->where('id_proses',$id_proses);
        $this->db->join('member','member.id=hasil_rfm.id_member','left');
        $data['dataTeamTidakLoyal'] = $this->db->get('hasil_rfm')->result();

        $data['page'] = "v_diagram";
        $data['output'] = $this->crud->render();
        $this->load->view('admin/'.$this->session->userdata('theme').'/v_index',$data);
    }
}