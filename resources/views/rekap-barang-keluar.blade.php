<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <x-slot:style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center row">
                        <div class="dropdown col">
                            <button class="btn btn-default" id="monthPicker">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span>{{ date('F Y') }}</span> <!-- Default bulan saat ini -->
                            </button>
                        </div>
                        <a href="{{ route('projek.create') }}" class="btn btn-primary col-2">
                            <i class="fas fa-plus"></i> Tambah Projek
                        </a>
                    </div>
                    
                    <div class="card-body">
                      <table id="tabel" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Project/Tempat</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data dari server akan diisi di sini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total</td>
                                <td id="totalJumlah" class="font-weight-bold"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </section>

    <x-slot:script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(function () {
                const formatRupiah = (value) => {
                    return `Rp${parseFloat(value).toLocaleString('id-ID')}`;
                };

                // Inisialisasi bulan saat ini
                let currentDate = moment();
                let startDate = currentDate.clone().startOf('month');
                let endDate = currentDate.clone().endOf('month');

                // Konfigurasi DataTable
                const table = $("#tabel").DataTable({
                    "dom": 'Bfrtip', // Hilangkan 'l' (length menu) dan 'f' (search)
                    "lengthChange": false, // Sembunyikan "Show entries"
                    "searching": false, // Nonaktifkan search box
                    "ordering": false,
                    "processing": true,
                    "serverSide": true,
                    "columnDefs": [
                        { "width": '20px', "targets": 0 },
                        { "width": '20px', "targets": 4 },
                    ],
                    "ajax": {
                        "url": "/rekap-projek/data",
                        "data": function(d) {
                            d.start_date = startDate.format('YYYY-MM-DD');
                        }
                    },
                    "buttons": [
                        {
                            "extend": 'excel',
                            "text": '<i class="fa-solid fa-download"></i> Export',
                            "className": 'btn btn-success btn-sm',
                            "exportOptions": {
                                "columns": [0, 1, 2, 3]
                            },
                            "title": 'Laporan Barang Keluar'
                        }
                    ],
                    "columns": [
                        { 
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        { 
                            "data": "nama",
                            "name": "projek.nama" // Untuk sorting/filter server-side
                        },
                        { 
                            "data": "status",
                            "render": function(data) {
                                const badgeClass = data === 'done' ? 'bg-success' : 'bg-warning';
                                return `<span class="badge ${badgeClass}">${data.toUpperCase()}</span>`;
                            }
                        },
                        { 
                            "data": "total",
                            "render": function(data) {
                                return formatRupiah(data);
                            },
                            "className": "text-right"
                        },
                        { 
                            "data": "id",
                            "render": function(data, type, row) {
                                const icon = row.status === 'done' ? 
                                    '<i class="fas fa-times btn btn-danger btn-xs"></i>' : 
                                    '<i class="fas fa-check btn btn-success btn-xs"></i>';
                                
                                const action = row.status === 'done' ? 'loading' : 'done';
                                
                                return `
                                    <button class="btn btn-sm btn-status" 
                                        data-id="${data}" 
                                        data-action="${action}">
                                        ${icon}
                                    </button>
                                `;
                            },
                            "orderable": false,
                            "searchable": false,
                            "className": "text-center"
                        }
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        const api = this.api();
                        const total = api
                            .column(3, { search: 'applied' })
                            .data()
                            .reduce((a, b) => a + parseFloat(b), 0);

                        $('#totalJumlah').html(formatRupiah(total));
                    }
                });

                // Konfigurasi Month Picker
                $('#monthPicker').daterangepicker({
                    startDate: startDate,
                    endDate: endDate,
                    autoUpdateInput: true,
                    locale: {
                        format: 'MM/YYYY',
                        separator: ' - ',
                        applyLabel: 'Pilih',
                        cancelLabel: 'Batal',
                        daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                        monthNames: [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ],
                    },
                    opens: 'left',
                    showDropdowns: true,
                    minYear: 2020,
                    maxYear: parseInt(moment().format('YYYY'), 10),
                    singleDatePicker: false,
                    alwaysShowCalendars: true,
                    ranges: {
                        'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, function(start, end, label) {
                    startDate = start;
                    endDate = end;
                    $('#monthPicker span').html(start.format('MMMM YYYY'));
                    table.ajax.reload();
                });

                // Handle cancel
                $('#monthPicker').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).find('span').html(moment().format('MMMM YYYY'));
                    startDate = moment().startOf('month');
                    endDate = moment().endOf('month');
                    table.ajax.reload();
                });

                $('#tabel').on('click', '.btn-status', function() {
                    const button = $(this);
                    const id = button.data('id');
                    const action = button.data('action');
                    
                    Swal.fire({
                        title: 'Ubah Status?',
                        text: `Yakin ingin mengubah status menjadi ${action.toUpperCase()}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Ubah!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/rekap-projek/${id}/status`,
                                method: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    status: action
                                },
                                success: function(response) {
                                    table.ajax.reload();
                                    Swal.fire('Berhasil!', 'Status telah diubah.', 'success');
                                },
                                error: function(xhr) {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </x-slot:script>
</x-layout>