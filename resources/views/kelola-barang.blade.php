<x-layout>

    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
  
                <div class="card">
                    <div class="card-body">
                      <table id="tabel" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>SMP</td>
                                <td>SEMEN MERAH PUTIH</td>
                                <td>30</td>
                                <td>
                                    <button class="btn btx-xs btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn btx-xs btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
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
              $.extend(true, $.fn.dataTable.Buttons.defaults, {
                dom: {
                  button: {
                    className: 'btn btn-primary btn-sm'
                  }
                }
              });
              const table = $("#tabel").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                ordering: false,
                columnDefs: [
                  { "width": "20px", "targets": [0, 1, 3] },
                  { "width": "150px", "targets": 4 } 
                ],
                buttons: [
                  {
                    text: '<i class="fas fa-plus"></i> Tambah Data',
                    action: function (e, dt, node, config) {
                      // Ganti ini sesuai kebutuhan, bisa buka modal atau redirect
                      window.location.href = "/barang/create";
                    }
                  },
                ]
              });

              // Tempatkan tombol ke posisi kiri atas
              table.buttons().container().appendTo('#tabel_wrapper .col-md-6:eq(0)');
            });
        </script>
      </x-slot:script>

</x-layout>


