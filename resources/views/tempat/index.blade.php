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
                            <th>Nama Tempat</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($tempats as $index => $tempat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tempat->nama }}</td>
                                    <td>
                                        <a href="{{ route('tempat.edit', $tempat->id) }}" class="btn btx-xs btn-warning btn-sm">
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
                  "emptyTable": "Belum ada tempat"
                },
                "columnDefs": [
                  { "width": "20px", "targets": [0] },
                  { "width": "200px", "targets": [2] }
                ],
                "buttons": [
                  {
                    "text": '<i class="fas fa-plus"></i> Tambah Tempat',
                    action: function (e, dt, node, config) {
                      window.location.href = "{{ route('tempat.create') }}";
                    }
                  },
                ]
              });
              table.buttons().container().appendTo('#tabel_wrapper .col-md-6:eq(0)');
            });
        </script>
      </x-slot:script>
</x-layout>
