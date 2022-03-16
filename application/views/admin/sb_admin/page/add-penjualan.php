        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Member</h4>
                  <div class="form-group">
                    <?php if(empty($this->session->userdata('id_member'))){?>
                    <label for="exampleInputUsername1">Pilih Member</label>
                    <form method="post" action="<?php echo base_url('admin/Penjualan/addMember') ?>">
                      <select name="id" class="form-control">
                        <option value="">--Member--</option>
                        <?php  
                          foreach ($member as $key) {
                        ?>
                          <option value="<?php echo $key->id ?>">[<?php echo $key->no_member ?>] - <?php echo $key->nama?></option>
                        
                        <?php } ?>
                      </select>
                      <input type="submit" name="" value="Pilih" class="btn btn-gradient-primary mr-2">
                    </form>
                    <?php } ?>
                    </div>
                  <?php if(!empty($this->session->userdata('id_member'))){?>
                  <form class="forms-sample" method="post" action="<?php echo base_url("admin/Penjualan/cartMember") ?>">
                    <div class="form-group">
                      <label for="exampleInputUsername1">Nama Member</label>
                      <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $this->session->userdata('nama') ?>" disabled class="form-control">
                    </div>
                    <div class="row">
                      <div class="col-md-5"> 
                        <div class="form-group">
                          <label for="exampleInputUsername1">No. Member</label>
                          <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $this->session->userdata('no_member') ?>" disabled class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="exampleInputUsername1">Usia</label>
                          <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $this->session->userdata('usia') ?>" disabled class="form-control">
                        </div>
                      </div>
                    </div>
                        
                    <div class="form-group">
                      <label for="exampleInputUsername1">ALamat</label>
                      <textarea name="alamat" id="alamat" class="form-control" cols="30" rows="5" disabled=""><?php echo $this->session->userdata('alamat') ?></textarea>
                      <input type="hidden" id="id_member" name="id_member" value="<?php echo $this->session->userdata('id_member') ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputUsername1">Nama Barang</label>
                      <select name="id_kategori" class="form-control">

                        <option value="">Pilih Barang</option>
                        <?php  
                          foreach ($brg as $key) {
                        ?>
                          <option value="<?php echo $key->id?>">[<?php echo $key->kode_kacamata ?>] <?php echo $key->merk_kacamata ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputUsername1">Jumlah</label>
                      <input type="Number" class="form-control" name="qty" min="1"  placeholder="qty">
                    </div> 
                    <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    <a href="<?php echo base_url('admin/Penjualan/hapusSession') ?>">Batal</a>
                  </form>
                  <?php } ?>
                </div>
              </div>
            </div>

            
            <?php //if(!empty($this->session->userdata('id_member'))){?>
            <div class="col-md-8 grid-margin stretch-card">
            <!-- alert -->
            <?php if (!empty($this->session->userdata('alert'))) { ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
              <strong>Maaf!</strong> Stok barang <b><?php echo $this->session->userdata('merk_kacamata'); ?></b> tidak cukup.
            </div>
            <?php 
              } 
                $this->session->unset_userdata('alert');
                $this->session->unset_userdata('nama_barang');
            ?>
            <!-- alert -->
              <div class="card">
                <div class="card-body">
                  <h3>Order List</h3>
                  <table class="table">
                    <tr>
                      <td>No</td>
                      <td>Nama Barang</td>
                      <td>Harga</td>
                      <td>Qty</td>
                      <td>Sub Total</td>
                      <td>Aksi</td>
                    </tr>
                    <?php 
                    $no=1;
                    foreach ($this->cart->contents() as $item): ?>
                       
                    <tr>
                      <td><?php echo $no ?></td>
                      <td>[<?php echo $item["kode"]?>] - <?php echo $item["name"] ?></td>
                      <td><?php echo "Rp ".number_format ($item["price"]) ?></td>
                      <td><?php echo $item["qty"] ?></td>
                      <td><?php echo "Rp ".number_format ($item["subtotal"]) ?></td>
                      <td>
                        <form method="post" action="<?php echo base_url("admin/Penjualan/hapus_cart_penjualan") ?>">
                          <input type="hidden" value="<?php echo $item["rowid"] ?>" name="id">
                          <input type="submit" value="X" class="btn btn-danger">
                        </form>

                      </td>
                    </tr>

                    <?php 
                    $no++;
                    // $id_customer =  $item["options"]["id_customer"];
                    endforeach; ?>
                    <tr>
                      <td colspan="4" style="text-align: right;">
                        Total : 
                      </td>
                      <td>
                        <?php echo "Rp ".number_format($this->cart->total())  ?>
                      </td>
                      <td>
                        <form method="post" action="<?php echo base_url("admin/Penjualan/proses_penjualan") ?>">
                          <input type="hidden" name="id_penerima" value="<?php echo $this->session->userdata('id_member') ?>">
                          <button type="submit" class="btn btn-gradient-primary mr-2">Pesan</button>
                        </form>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <?php //} ?>
          </div>



        </div>
