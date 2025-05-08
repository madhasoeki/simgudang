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
                            <th>Nama Projek</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($projeks as $index => $projek)
                                
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $projek->nama }}</td>
                                    <td>
                                        <a href="{{ route('projek.edit', $projek->id) }}" class="btn btx-xs btn-warning btn-sm">
                                          <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
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
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false,
                "info": false,
                "language": {
                  "emptyTable": "Belum ada projek"
                },
                "columnDefs": [
                  { "width": "20px", "targets": [0] },
                  { "width": "200px", "targets": [2] }
                ],
                "buttons": [
                  {
                    "text": '<i class="fas fa-plus"></i> Tambah Projek',
                    action: function (e, dt, node, config) {
                      // Ganti ini sesuai kebutuhan, bisa buka modal atau redirect
                      window.location.href = "{{ route('projek.create') }}";
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


