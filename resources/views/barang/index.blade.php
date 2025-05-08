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
                                  @foreach($barangs as $index => $barang)
                                      <tr>
                                          <td>{{ $index + 1 }}</td>
                                          <td>{{ $barang->kode }}</td>
                                          <td>{{ $barang->nama }}</td>
                                          <td>{{ $barang->stok->jumlah ?? 0 }}</td>
                                          <td>
                                              <a href="{{ route('barang.edit', $barang->kode) }}" class="btn btn-warning btn-sm">
                                                  <i class="fas fa-edit"></i> Edit
                                              </a>
                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
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
                  "emptyTable": "Belum ada barang yang ditambahkan",
                  "zeroRecords": "Tidak ada data yang ditemukan",
                },
                "columnDefs": [
                  { "width": "20px", "targets": [0, 1, 3] },
                  { "width": "150px", "targets": 4 } 
                ],
                "buttons": [
                  {
                    "text": '<i class="fas fa-plus"></i> Tambah Data Barang',
                    action: function (e, dt, node, config) {
                      // Ganti ini sesuai kebutuhan, bisa buka modal atau redirect
                      window.location.href = "{{ route('barang.create') }}";
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


