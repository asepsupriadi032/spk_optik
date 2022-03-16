<form action="<?php echo base_url('admin/Kalkulasi/insertKalkulasi') ?>" method="post"> 
  <div class="col-md-10">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Judul</label>
        </div>
      </div>
      <div class="col-md-5">
        <input type="text" name="judul" id="judul" class="form-control">
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Tanggal</label>
        </div>
      </div>
      <div class="col-md-3">
        <input type="date" name="tanggal" id="tanggal" class="form-control">
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Periode Awal</label>
        </div>
      </div>
      <div class="col-md-3">
        <input type="date" name="periode_awal" id="periode_awal" class="form-control">
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="exampleInputName2">Periode Akhir</label>
        </div>
      </div>
      <div class="col-md-3">
        <input type="date" name="periode_akhir" id="periode_akhir" class="form-control">
      </div>
    </div>
    <div class="row">
      <div class="col-md-10" style="text-align: right;">
        <input type="submit" value="Simpan" class="btn btn-primary">
      </div>
    </div>
  </div>
</form>