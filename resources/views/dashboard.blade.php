<x-layout>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="row">
        <div class="col-sm">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Barang Masuk</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-dolly"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-sm">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>

              <p>Barang Keluar</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-boxes-packing"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-sm">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>

              <p>Stok Kritis</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-box"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Barang Keluar Hari Ini</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: block;">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>QTY</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Tempat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                        <td></td>
                        <td>Apartemen</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                        <td></td>
                        <td>Apartemen</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                        <td></td>
                        <td>Apartemen</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Barang Masuk Hari Ini</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: block;">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th>QTY</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>SMP</td>
                        <td>27/12/2024</td>
                        <td>SEMEN MERAH PUTIH</td>
                        <td>30</td>
                        <td>SAK</td>
                        <td>Rp81.000</td>
                        <td>Rp2.430.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>

</x-layout>