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
                                    <span>{{ date('F Y') }}</span>
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="tabel" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Satuan</th>
                                        <th>Stok Awal</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Seharusnya</th>
                                        <th>Lapangan</th>
                                        <th>Selisih</th>
                                        <th>Harga</th>
                                        <th>Approve</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                let selectedMonth = moment().format('YYYY-MM');

                const formatRupiah = (value) => {
                    return `Rp${parseFloat(value).toLocaleString('id-ID')}`;
                };

                const table = $('#tabel').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/opname/data",
                        data: function(d) {
                            d.bulan = selectedMonth;
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'nama_barang', name: 'barang.nama' },
                        { data: 'satuan', name: 'barang.satuan' },
                        { data: 'stock_awal' },
                        { data: 'total_masuk' },
                        { data: 'total_keluar' },
                        { 
                            data: null,
                            render: row => row.stock_awal + row.total_masuk - row.total_keluar 
                        },
                        { data: 'total_lapangan' },
                        { data: 'miss' },
                        { 
                            data: 'harga',
                            render: data => formatRupiah(data),
                            className: "text-right"
                        },
                        { 
                            data: null,
                            render: function(data) {
                                if (data.approved) {
                                    return '<span class="badge bg-success">Approved</span>';
                                } else {
                                    return `<button class="btn btn-sm btn-primary btn-approve" data-id="${data.id}">
                                                Approve
                                            </button>`;
                                }
                            },
                            orderable: false,
                            searchable: false,
                            className: "text-center"
                        },
                    ]
                });

                // Date picker
                $('#monthPicker').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    locale: {
                        format: 'MM/YYYY',
                        applyLabel: 'Pilih',
                        cancelLabel: 'Batal',
                        monthNames: moment.months(),
                        daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                    },
                }, function(start) {
                    selectedMonth = start.format('YYYY-MM');
                    $('#monthPicker span').html(start.format('MMMM YYYY'));
                    table.ajax.reload();
                });

                // Approve handler
                $('#tabel').on('click', '.btn-approve', function() {
                    const id = $(this).data('id');
                    Swal.fire({
                        title: 'Approve Opname?',
                        text: 'Setelah disetujui, tidak bisa dibatalkan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Setujui',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/opname/${id}/approve`,
                                method: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(res) {
                                    Swal.fire('Berhasil', res.message, 'success');
                                    table.ajax.reload();
                                },
                                error: function(err) {
                                    Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </x-slot:script>
</x-layout>
