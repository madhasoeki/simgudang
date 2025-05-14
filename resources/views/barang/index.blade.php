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
                                              <form action="{{ route('barang.destroy', $barang->kode) }}" method="POST" style="display:inline-block">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="button" class="btn btn-danger btn-sm btn-delete-barang"><i class="fas fa-trash"></i> Hapus</button>
                                              </form>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                      window.location.href = "{{ route('barang.create') }}";
                    }
                  },
                ]
              });

              table.buttons().container().appendTo('#tabel_wrapper .col-md-6:eq(0)');

              // SweetAlert untuk hapus
              $('#tabel').on('click', '.btn-delete-barang', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                  title: 'Yakin ingin menghapus barang ini?',
                  text: "Data yang dihapus tidak dapat dikembalikan!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Ya, hapus!',
                  cancelButtonText: 'Batal'
                }).then((result) => {
                  if (result.isConfirmed) {
                    form.submit();
                  }
                });
              });
            });
        </script>
      </x-slot:script>

</x-layout>


