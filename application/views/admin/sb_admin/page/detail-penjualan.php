        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Member</h4>
                  <form class="forms-sample" method="post" action="<?php echo base_url("admin/Penjualan/cartMember") ?>">
                    <div class="form-group">
                      <label for="exampleInputUsername1">Nama Member</label>
                      <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $member->nama ?>" disabled class="form-control">
                    </div>
                    <div class="row">
                      <div class="col-md-5"> 
                        <div class="form-group">
                          <label for="exampleInputUsername1">No. Member</label>
                          <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $member->no_member ?>" disabled class="form-control">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="exampleInputUsername1">Usia</label>
                          <input type="text" name="nama_toko" id="nama_toko" value="<?php echo $member->usia ?>" disabled class="form-control">
                        </div>
                      </div>
                    </div>
                        
                    <div class="form-group">
                      <label for="exampleInputUsername1">ALamat</label>
                      <textarea name="alamat" id="alamat" class="form-control" cols="30" rows="5" disabled=""><?php echo $member->alamat ?></textarea>
                      <input type="hidden" id="id_member" name="id_member" value="<?php echo $this->session->userdata('id_member') ?>">
                    </div>
                </div>
              </div>
            </div>

            
            <?php //if(!empty($this->session->userdata('id_member'))){?>
            <div class="col-md-8 grid-margin stretch-card">
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
                    </tr>
                    <?php 
                    $total = 0;
                    $no=1;
                    // var_dump($detailPenjualan); die();
                    foreach ($detailPenjualan as $item): ?>
                       
                    <tr>
                      <td><?php echo $no ?></td>
                      <td>[<?php echo $item->kode_kacamata; ?>] - <?php echo $item->merk_kacamata ?></td>
                      <td><?php echo "Rp ".number_format ($item->harga) ?></td>
                      <td><?php echo $item->qty ?></td>
                      <td><?php echo "Rp ".number_format ($item->sub_total) ?></td>
                    </tr>

                    <?php 
                    $total = $total + $item->sub_total;
                    $no++;
                    // $id_customer =  $item["options"]["id_customer"];
                    endforeach; ?>
                    <tr>
                      <td colspan="4" style="text-align: right;">
                        Total : 
                      </td>
                      <td>
                        <?php echo "Rp ".number_format($total); ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>



        </div>
