<form action="<?php echo base_url('admin/Kalkulasi/prosesKriteria') ?>" method="post"> 
  <div class="col-md-10">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Judul</label>
        </div>
      </div>
      <div class="col-md-5">
        <input type="text" name="judul" id="judul" class="form-control" value="<?php echo $periode->judul ?>" disabled>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Tanggal</label>
        </div>
      </div>
      <div class="col-md-3">
        <input type="text" name="tanggal" id="tanggal" class="form-control" disabled="" value="<?php echo $periode->tanggal_kalkulasi ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Periode</label>
        </div>
      </div>
      <div class="col-md-5">
        <input type="text" name="periode_akhir" id="periode_akhir" class="form-control" disabled="" value="<?php echo $periode->periode_awal.'/'.$periode->periode_akhir; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-md-7" style="text-align: right;">
        <input type="hidden" value="<?php echo $periode->id ?>" name='id'>
        <input type="submit" value="Proses" class="btn btn-primary">
      </div>
    </div>
  </div>
</form>