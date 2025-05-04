
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <x-slot:style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
  
                <div class="card">
                    <div class="card-header">
                        <div class="dropdown mr-2">
                            <button class="btn btn-default" id="dateFilterDropdown">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span>Pilih Rentang Tanggal</span>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Project/Tempat</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Al-Akram</td>
                                <td>DONE</td>
                                <td>5460000</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total</td>
                                <td id="totalJumlah" class="font-weight-bold"></td>
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

        {{-- datepicker library --}}
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(function () {
                const formatRupiah = (value) => {
                    return `Rp${parseFloat(value).toLocaleString('id-ID')}`;
                };

                const table = $("#example1").DataTable({
                    "searching": false,
                    "paging": false,
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "ordering": false,
                    "columnDefs": [
                        { 
                            "width": "20px", 
                            "targets": 0 
                        },
                        {
                            "targets": 3,
                            "render": (data) => formatRupiah(data),
                            "className": 'text-right'
                        }
                    ],
                    "buttons": [
                        {
                            "extend": 'excel',
                            "text": '<i class="fa-solid fa-download"></i> Export',
                            "className": 'btn btn-success btn-sm',
                            "title": "REKAPAN TOTAL",
                            "messageTop": "Periode Desember 2022",
                            "customize": function (xlsx) {
                                const sheet = xlsx.xl.worksheets['sheet1.xml'];
                                
                                // Tambahkan border ke semua sel
                                $('row c', sheet).attr('s', '2'); // Style index 2 untuk border
                                
                                // Tambahkan total
                                const total = $('#totalJumlah').text();
                                const lastRow = $('row:last', sheet).attr('r');
                                const newRow = parseInt(lastRow) + 1;
                                
                                $('row:last', sheet).after(`
                                    <row r="${newRow}">
                                        <c t="inlineStr" r="A${newRow}" s="3">
                                            <is><t>Total</t></is>
                                        </c>
                                        <c r="D${newRow}" t="n" s="4">
                                            <v>${total.replace(/[^\d]/g, '')}</v>
                                        </c>
                                    </row>
                                `);

                                // Tambahkan style number format
                                $('xf:applyNumberFormats', sheet).attr('count', '2');
                            }
                        }
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        const api = this.api();
                        const total = api
                            .column(3, { filter: 'applied' })
                            .data()
                            .reduce((a, b) => a + parseInt(b), 0);

                        $('#totalJumlah').html(formatRupiah(total));
                    }
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                // Membuat tampilan filterisaasi tanggal

                // Hapus opsi Bandingkan
                $('#bandingkanOption').remove();
                
                // Inisialisasi date range picker
                const locale = {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                };

                $('#dateFilterDropdown').daterangepicker({
                autoUpdateInput: false,
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
                });

                // Handle date selection
                $('#dateFilterDropdown').on('apply.daterangepicker', function(ev, picker) {
                    $(this).find('span').html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                    // Tambahkan logika filter disini
                    console.log('Selected dates:', picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
                });

                // Handle cancel
                $('#dateFilterDropdown').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).find('span').html('Pilih Rentang Tanggal');
                });

                // Tampilkan calendar saat klik custom
                $('#customDateTrigger').click(function(e) {
                    e.preventDefault();
                    $('#dateFilterDropdown').data('daterangepicker').toggle();
                    $('.dropdown-menu').addClass('show');
                });
            });
        </script>
      </x-slot:script>

</x-layout>