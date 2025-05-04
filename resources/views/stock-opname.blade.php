<x-layout>

    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
  
                <div class="card">
                    <div class="card-header row">
                        <h3 class="card-title col"></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok Awal</th>
                            <th>Total Masuk</th>
                            <th>Total Keluar</th>
                            <th>Total</th>
                            <th>Lapangan</th>
                            <th>Miss</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>SMP</td>
                                <td>SEMEN MERAH PUTIH</td>
                                <td>SAK</td>
                                <td>20</td>
                                <td>0</td>
                                <td>10</td>
                                <td>10</td>
                                <td>10</td>
                                <td>0</td>
                                <td>2.450.000</td>
                                <td>
                                    <button type="button" class="btn btn-block btn-warning btn-xs"><i class="fas fa-edit"></i> Input Data Lapangan</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>SMP</td>
                                <td>SEMEN MERAH PUTIH</td>
                                <td>SAK</td>
                                <td>20</td>
                                <td>0</td>
                                <td>10</td>
                                <td>10</td>
                                <td>10</td>
                                <td>1</td>
                                <td>2.450.000</td>
                                <td>
                                  <button type="button" class="btn btn-block btn-warning btn-xs"><i class="fas fa-edit"></i> Input Data Lapangan</button>
                                </td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>

      <x-slot:script>
        <script>
            $(function () {
              $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false,
                "columnDefs": [
                  { "width": "20px", "targets": 0 } // Mengatur lebar kolom pertama (indeks 0) menjadi 20px
                ],
                "buttons": ["excel", "pdf"],
                "createdRow": function(row, data, dataIndex) {
                  const dataMiss = parseInt(data[9].replace(/[^0-9]/g, ''), 10); // Ambil kolom QTY
                  if (dataMiss > 0) {
                    $(row).addClass('bg-danger text-white');
                  }
                }
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            });
        </script>
      </x-slot:script>

</x-layout>