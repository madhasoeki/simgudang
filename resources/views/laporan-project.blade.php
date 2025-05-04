<x-layout>

  <x-slot:title>{{ $title }}</x-slot:title>

  <x-slot:style>
    {{-- library datepicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
  </x-slot:style>

  <section class="content">
    <div class="card">
      <div class="card-header">
        {{-- filter tanggal --}}
        <div class="dropdown mr-2">
          <button class="btn btn-default" id="dateFilterDropdown">
            <i class="far fa-calendar-alt mr-2"></i>
            <span>Pilih Rentang Tanggal</span>
          </button>
        </div>
      </div>
      <div class="card-body">

        {{-- dropdown filter --}}
        <select id="projectFilter" class="form-control mb-2" style="width: 200px;">
          <option value="">--- Pilih Project ---</option>
          <option value="Al-Akram">Al-Akram</option>
          <option value="Al-Hijrah">Al-Hijrah</option>
          <option value="BPD">BPD</option>
          <option value="Masjid Ka'bah">Masjid Ka'bah</option>
          <option value="Masjid Ismuhu Yahya">Masjid Ismuhu Yahya</option>
          <option value="Pondok Qur'an">Pondok Qur'an</option>
        </select>
        
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
              <th>Keterangan</th>
              <th>Jumlah</th>
              <th>Project</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data untuk proyek Al-Akram -->
            <tr>
                <td>1</td>
                <td>SMP</td>
                <td>07/12/2022</td>
                <td>SEMEN MERAH PUTIH</td>
                <td>30</td>
                <td>SAK</td>
                <td>80000</td>
                <td>Gedung</td>
                <td>2400000</td>
                <td>Al-Akram</td>
            </tr>
            <tr>
                <td>2</td>
                <td>SMP</td>
                <td>08/12/2022</td>
                <td>SEMEN MERAH PUTIH</td>
                <td>25</td>
                <td>SAK</td>
                <td>80000</td>
                <td>Masjid</td>
                <td>2000000</td>
                <td>Al-Akram</td>
            </tr>
            <!-- Data untuk proyek Al-Hijrah -->
            <tr>
                <td>3</td>
                <td>HJM</td>
                <td>10/12/2022</td>
                <td>SEMEN GRESIK</td>
                <td>25</td>
                <td>SAK</td>
                <td>75000</td>
                <td>Gedung</td>
                <td>1875000</td>
                <td>Al-Hijrah</td>
            </tr>
            <tr>
                <td>4</td>
                <td>HJM</td>
                <td>11/12/2022</td>
                <td>SEMEN GRESIK</td>
                <td>35</td>
                <td>SAK</td>
                <td>75000</td>
                <td>Masjid</td>
                <td>2625000</td>
                <td>Al-Hijrah</td>
            </tr>
            <!-- Data untuk proyek BPD -->
            <tr>
                <td>5</td>
                <td>BPD</td>
                <td>15/12/2022</td>
                <td>BATU BATA MERAH</td>
                <td>100</td>
                <td>BATU</td>
                <td>3500</td>
                <td>Gedung</td>
                <td>350000</td>
                <td>BPD</td>
            </tr>
            <tr>
                <td>6</td>
                <td>BPD</td>
                <td>16/12/2022</td>
                <td>BATU BATA MERAH</td>
                <td>120</td>
                <td>BATU</td>
                <td>3500</td>
                <td>Masjid</td>
                <td>420000</td>
                <td>BPD</td>
            </tr>
            <!-- Data untuk proyek Masjid Ka'bah -->
            <tr>
                <td>7</td>
                <td>MKD</td>
                <td>20/12/2022</td>
                <td>SEMEN JAYAMIX</td>
                <td>50</td>
                <td>SAK</td>
                <td>90000</td>
                <td>Gedung</td>
                <td>4500000</td>
                <td>Masjid Ka'bah</td>
            </tr>
            <tr>
                <td>8</td>
                <td>MKD</td>
                <td>21/12/2022</td>
                <td>SEMEN JAYAMIX</td>
                <td>60</td>
                <td>SAK</td>
                <td>90000</td>
                <td>Masjid</td>
                <td>5400000</td>
                <td>Masjid Ka'bah</td>
            </tr>
            <!-- Data untuk proyek Masjid Ismuhu Yahya -->
            <tr>
                <td>9</td>
                <td>MIY</td>
                <td>25/12/2022</td>
                <td>BATU BATA MERAH</td>
                <td>80</td>
                <td>BATU</td>
                <td>4000</td>
                <td>Gedung</td>
                <td>320000</td>
                <td>Masjid Ismuhu Yahya</td>
            </tr>
            <tr>
                <td>10</td>
                <td>MIY</td>
                <td>26/12/2022</td>
                <td>BATU BATA MERAH</td>
                <td>90</td>
                <td>BATU</td>
                <td>4000</td>
                <td>Masjid</td>
                <td>360000</td>
                <td>Masjid Ismuhu Yahya</td>
            </tr>
            <!-- Data untuk proyek Pondok Qur'an -->
            <tr>
                <td>11</td>
                <td>PQR</td>
                <td>05/01/2023</td>
                <td>SEMEN MERAH PUTIH</td>
                <td>20</td>
                <td>SAK</td>
                <td>75000</td>
                <td>Gedung</td>
                <td>1500000</td>
                <td>Pondok Qur'an</td>
            </tr>
            <tr>
                <td>12</td>
                <td>PQR</td>
                <td>06/01/2023</td>
                <td>SEMEN MERAH PUTIH</td>
                <td>30</td>
                <td>SAK</td>
                <td>75000</td>
                <td>Masjid</td>
                <td>2250000</td>
                <td>Pondok Qur'an</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8" class="text-right font-weight-bold">Total:</td>
              <td id="totalJumlah" class="font-weight-bold"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>

  <x-slot:script>

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
      $(function () {
        const formatRupiah = (value) => {
          return `Rp${parseFloat(value).toLocaleString('id-ID')}`;
        };

        const table = $("#tabel").DataTable({
          responsive: true,
          lengthChange: false,
          autoWidth: false,
          ordering: false,
          columnDefs: [
            { width: "20px", targets: 0 },
            { targets: 9, visible: false }, // Sembunyikan kolom Project
            { 
              targets: [6, 8], // Kolom Harga dan Jumlah
              render: (data) => formatRupiah(data),
              className: 'text-right'
            }
          ],
          dom: '<"row mb-2"<"col-md-6"B><"col-md-6 d-flex justify-content-end"f>>rtip',
          buttons: [
    {
      extend: 'excel',
      text: 'Excel',
      title: function() {
        const project = $('#projectFilter').val() || 'Semua Project';
        return `Laporan Project ${project}`;
      },
      messageTop: function() {
        return 'Periode November - Desember 2025';
      },
      customize: function (xlsx) {
        const sheet = xlsx.xl.worksheets['sheet1.xml'];
        
        // Perbaikan struktur XML untuk menghindari warning
        const lastRow = $('row:last', sheet).attr('r');
        const newRow = parseInt(lastRow) + 1;
        
        // Format sel total dengan benar
        $('row:last', sheet).after(`
          <row r="${newRow}">
            <c t="inlineStr" r="A${newRow}">
              <is><t>Total</t></is>
            </c>
            <c r="I${newRow}" t="n">
              <v>${$('#totalJumlah').text().replace(/[^\d]/g, '')}</v>
            </c>
          </row>
        `);

        // Tambahkan style number format
        $('xf:applyNumberFormats', sheet).attr('count', '2');
      },
      exportOptions: {
        columns: [0,1,2,3,4,5,6,7,8], // Sembunyikan kolom Project (index 9)
        format: {
          body: function(data, row, column, node) {
            // Pertahankan format Rp untuk kolom Harga dan Jumlah
            return (column === 6 || column === 8) ? $(node).text() : data;
          }
        }
      }
    },
    {
            extend: 'pdf',
            text: 'PDF',
            orientation: 'landscape',
            pageSize: 'A4',
            title: function() {
              const project = $('#projectFilter').val() || 'Semua Project';
              return `Laporan Project ${project}`;
            },
            messageTop: 'Periode November - Desember 2025',
            customize: function (doc) {
        // Struktur lengkap dengan judul
        doc.content = [
          { 
            text: `Laporan Project ${$('#projectFilter').val() || 'Semua Project'}`, 
            fontSize: 16, 
            alignment: 'center', 
            margin: [0, 0, 0, 10] 
          },
          { 
            text: 'Periode November - Desember 2025', 
            fontSize: 12, 
            alignment: 'center', 
            margin: [0, 0, 0, 15] 
          },
          {
            table: {
              headerRows: 1,
              widths: [20, 30, 50, '*', 25, 40, 60, 60, 60], // Sesuaikan lebar kolom
              body: [
                // Header
                ['No','Kode','Tanggal','Nama Barang','QTY','Satuan','Harga','Keterangan','Jumlah'],
                // Data
                ...table.rows({search: 'applied'}).data().toArray().map(row => [
                  row[0], // No
                  row[1], // Kode
                  row[2], // Tanggal
                  row[3], // Nama Barang
                  row[4], // QTY
                  row[5], // Satuan
                  row[6], // Harga (Rp)
                  row[7], // Keterangan
                  row[8]  // Jumlah (Rp)
                ])
              ]
            }
          },
          {
            text: `Total: ${$('#totalJumlah').text()}`,
            margin: [0, 15, 0, 0],
            alignment: 'right',
            bold: true,
            fontSize: 12
          }
        ];

        // Styling khusus
        doc.styles.tableHeader = {
          fillColor: '#2c3e50',
          color: 'white',
          bold: true,
          fontSize: 10
        };
        
        doc.defaultStyle = {
          fontSize: 9,
          lineHeight: 1.2
        };
      },
            exportOptions: {
              columns: [0,1,2,3,4,5,6,7,8],
              format: {
                body: function (data, row, column, node) {
                  return (column === 6 || column === 8) ? $(node).text() : data;
                }
              }
            }
          }
        ],
          language: {
            info: "",
            infoEmpty: "",
            infoFiltered: "",
            lengthMenu: "Menampilkan _MENU_ data per halaman",
          },
          footerCallback: function (row, data, start, end, display) {
            const api = this.api();
            const total = api
              .column(8, { filter: 'applied', order: 'current' })
              .data()
              .reduce((a, b) => a + parseInt(b), 0);

            $('#totalJumlah').html(formatRupiah(total));
          },
          rowCallback: function (row, data, index) {
            $('td:eq(0)', row).html(index + 1);
          }
        });

        // Handle filter project
        $('#projectFilter')
          .appendTo('#tabel_wrapper .col-md-6:eq(1)')
          .on('change', function() {
            const val = $.fn.dataTable.util.escapeRegex(this.value);
            if (val) {
              table.column(9).search(`^${val}$`, true, false).draw();
              $('#tabel').show();
            } else {
              table.search('').draw();
              $('#tabel').hide();
            }
          });

        // Inisialisasi awal
        table.buttons().container().appendTo('#tabel_wrapper .col-md-6:eq(0)');
        $('#tabel_wrapper .dataTables_filter').remove();
        $('#tabel').hide();


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