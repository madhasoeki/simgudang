<x-layout>

    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
  
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="tabelMiss" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Miss</th>
                            <th>Harga</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>SMP</td>
                                <td>SEMEN MERAH PUTIH</td>
                                <td>SAK</td>
                                <td>1</td>
                                <td>2.450.000</td>
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
              $("#tabelMiss").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false,
                "columnDefs": [
                  { "width": "20px", "targets": 0 } // Mengatur lebar kolom pertama (indeks 0) menjadi 20px
                ],
              }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            });
        </script>
      </x-slot:script>

</x-layout>