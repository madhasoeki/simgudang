<x-layout>
    <x-slot:title>Catat Barang Keluar</x-slot:title>

    <x-slot:style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
  
                <div class="card">
                    <div class="card-header row">
                        <div class="dropdown mr-2 col">
                            <button class="btn btn-default" id="dateFilterDropdown">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span>Pilih Rentang Tanggal</span>
                            </button>
                        </div>
                        <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-block btn-primary col-sm-2">
                            <i class="fas fa-plus"></i>
                            Tambah Barang
                        </a>
                      </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="tabel" class="table table-bordered table-striped">
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
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7">Total</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            // Deklarasikan variabel table di level global
            let table;
            let startDate = moment().subtract(6, 'days');
            let endDate = moment();

            $(document).ready(function () {
                // Inisialisasi DataTable
                table = $("#tabel").DataTable({
                    "dom": '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "ordering": false,
                    "info": false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "/transaksi-keluar/data",
                        "data": function (d) {
                            d.start_date = startDate.format('YYYY-MM-DD');
                            d.end_date = endDate.format('YYYY-MM-DD');
                            return d;
                        }
                    },
                    "language": {
                        "emptyTable": "Belum ada data barang keluar"
                    },
                    "columns": [
                        { "data": "DT_RowIndex" },
                        { "data": "kode" },
                        { 
                            "data": "tanggal",
                            "render": function(data) {
                                return moment(data).format('DD/MM/YYYY');
                            }
                        },
                        { "data": "nama_barang" },
                        { "data": "qty" },
                        { "data": "satuan" },
                        { 
                            "data": "harga",
                            "render": function(data) {
                                return 'Rp' + parseFloat(data).toLocaleString('id-ID');
                            }
                        },
                        { 
                            "data": "jumlah",
                            "render": function(data) {
                                return 'Rp' + parseFloat(data).toLocaleString('id-ID');
                            }
                        },
                        { "data": "keterangan" },
                        { "data": "tempat" },
                    
                        {
                            "data": null,
                            "orderable": false,
                            "searchable": false,
                            "render": function(data, type, row) {
                                let editUrl = `/transaksi-keluar/${row.id}/edit`;
                                let deleteUrl = `/transaksi-keluar/${row.id}`;
                                return `
                                    <a href=\"${editUrl}\" class=\"btn btn-warning btn-sm\"><i class=\"fas fa-edit\"></i> Edit</a>
                                    <form action=\"${deleteUrl}\" method=\"POST\" style=\"display:inline-block\" class=\"form-delete-transaksi-keluar\">
                                        <input type=\"hidden\" name=\"_token\" value=\"${$('meta[name=csrf-token]').attr('content')}\">
                                        <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                        <button type=\"button\" class=\"btn btn-danger btn-sm btn-delete-transaksi-keluar\"><i class=\"fas fa-trash\"></i> Hapus</button>
                                    </form>
                                `;
                            }
                        }
                        ],
                    "buttons": [
                        {
                            extend: 'excel',
                            text: '<i class="fa-solid fa-download"></i> Export',
                            className: 'btn btn-success btn-sm',
                            title: "REKAPAN BARANG KELUAR",
                            messageTop: function() {
                                return 'Periode: ' + startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY');
                            },
                            exportOptions: {
                                columns: ':not(:first-child)' // Kecualikan kolom pertama
                            },
                            customize: function (xlsx) {
                                const sheet = xlsx.xl.worksheets['sheet1.xml'];
                                const $ = window.$;

                                // Hitung total kolom Jumlah
                                const total = table.column(7).data().reduce((sum, val) => {
                                    const angkaBersih = val.toString().replace(/[^\d]/g, '');
                                    return sum + parseInt(angkaBersih || '0') / 100;
                                }, 0);

                                // Cari elemen <sheetData>
                                const sheetData = $('sheetData', sheet);

                                // Hitung baris terakhir
                                const lastRow = $('row', sheet).length + 1;

                                // Buat elemen baris total sebagai XML string
                                const totalRow = `
                                    <row r="${lastRow}">
                                        <c t="inlineStr" r="A${lastRow}">
                                            <is><t>Total</t></is>
                                        </c>
                                        <c t="inlineStr" r="H${lastRow}">
                                            <is><t>Rp${total.toLocaleString('id-ID')}</t></is>
                                        </c>
                                    </row>
                                `;

                                // Tambahkan ke dalam sheetData
                                sheetData.append(totalRow);

                                // Merge A-G untuk label "Total"
                                let mergeCells = $('mergeCells', sheet);
                                if (mergeCells.length === 0) {
                                    const mergeCellsTag = `<mergeCells count="1"><mergeCell ref="A${lastRow}:G${lastRow}"/></mergeCells>`;
                                    $('worksheet', sheet).append(mergeCellsTag);
                                } else {
                                    mergeCells.attr('count', mergeCells.find('mergeCell').length + 1);
                                    mergeCells.append(`<mergeCell ref="A${lastRow}:G${lastRow}"/>`);
                                }
                            }
                        }
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        const api = this.api();
                        
                        // Ambil data kolom jumlah (kolom ke-7)
                        const total = api
                            .column(7, { search: 'applied' })
                            .data()
                            .reduce((sum, val) => {
                                
                                // Bersihkan format mata uang
                                const cleaned = val.toString()
                                    .replace(/[^\d]/g, '') // Hapus semua non-digit
                                    .slice(0, -2); // Hapus 2 digit terakhir (jika ada desimal)
                                
                                // Konversi ke numeric
                                const numericValue = parseFloat(val) || 0;
                                
                                return sum + numericValue;
                            }, 0);

                        // Format ulang ke mata uang
                        $(api.column(7).footer()).html(
                            'Rp' + (total).toLocaleString('id-ID') // Sesuaikan faktor pembagi
                        );
                    }
                });

                // Inisialisasi Date Range Picker
                const locale = {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Terapkan',
                    cancelLabel: 'Batal',
                    daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    monthNames: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ]
                };

                $('#dateFilterDropdown').daterangepicker({
                    startDate: startDate,
                    endDate: endDate,
                    autoUpdateInput: true,
                    locale: locale,
                    ranges: {
                        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                        '14 Hari Terakhir': [moment().subtract(13, 'days'), moment()],
                        '28 Hari Terakhir': [moment().subtract(27, 'days'), moment()],
                        '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                        'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
                        'Minggu Lalu': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                        'Bulan Ini': [moment().startOf('month'), moment().endOf('month')]
                    }
                }, function(start, end) {
                    startDate = start;
                    endDate = end;
                    $('#dateFilterDropdown span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                    table.ajax.reload(() => {
                        $('#dateFilterDropdown').data('daterangepicker').hide();
                    });
                });

                // Handle cancel date picker
                // SweetAlert untuk hapus transaksi keluar
                $('#tabel').on('click', '.btn-delete-transaksi-keluar', function(e) {
                    e.preventDefault();
                    const form = $(this).closest('form');
                    Swal.fire({
                        title: 'Yakin ingin menghapus transaksi ini?',
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
                $('#dateFilterDropdown').on('cancel.daterangepicker', function(ev, picker) {
                    startDate = moment().subtract(6, 'days');
                    endDate = moment();
                    $(this).find('span').html('Pilih Rentang Tanggal');
                    table.ajax.reload();
                    picker.hide();
                });
            });
        </script>
    </x-slot:script>

</x-layout>