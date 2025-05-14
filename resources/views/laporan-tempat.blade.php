<x-layout>

  <x-slot:title>{{ $title }}</x-slot:title>

  <x-slot:style>
    {{-- library datepicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
  </x-slot:style>

  <section class="content">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between">
            <button class="btn btn-default" id="monthPicker">
                <i class="far fa-calendar-alt mr-2"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </button>
        </div>
      </div>
      <div class="card-body">

        <select id="tempatFilter" class="form-control mb-2" style="width: 200px;">
          <option value="">--- Pilih Tempat ---</option>
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
              <th>Tempat</th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
                <td colspan="8" class="text-right font-weight-bold">Total:</td>
                <td id="totalJumlah" class="font-weight-bold"></td>
                <td></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>

  <x-slot:script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

      $(document).ready(function () {
          // Load data tempat ke dropdown
          $.ajax({
              url: "{{ route('tempat.list') }}",
              method: 'GET',
              success: function(data) {
                  const tempatFilter = $('#tempatFilter');
                  data.forEach(function(tempat) {
                      tempatFilter.append(`<option value="${tempat.id}">${tempat.nama}</option>`);
                  });
              },
              error: function(xhr) {
                  console.error('Gagal memuat data tempat:', xhr.responseText);
              }
          });
      });

      $(function () {
          // Inisialisasi datepicker
          const initDatePicker = () => {
              const defaultDate = moment().startOf('month');
              const picker = $('#monthPicker').daterangepicker({
                startDate: defaultDate,
                endDate: defaultDate.clone().endOf('month'),
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
                autoUpdateInput: true,
                alwaysShowCalendars: true,
                ranges: {
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    '2 Bulan Lalu': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
                    '3 Bulan Lalu': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
                }
            }, function(start, end) {
                currentMonth = start.format('YYYY-MM');
                $('#monthPicker span').html(start.format('MMMM YYYY'));
                if ($('#tempatFilter').val()) {
                    table.ajax.reload();
                }
            });

              // Handle initial render
              const initialStart = picker.data('daterangepicker').startDate;
              $('#monthPicker span').html(initialStart.format('MMMM YYYY'));
              
              return picker;
          };

          // Inisialisasi datepicker pertama kali
          let currentMonth = moment().format('YYYY-MM');
          const picker = initDatePicker();

          // Inisialisasi DataTable
          const table = $("#tabel").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan-tempat.data') }}",
                data: function(d) {
                    d.month = currentMonth;
                    d.tempat_id = $('#tempatFilter').val();
                }
            },
            columns: [
              // Sesuaikan dengan struktur HTML dan data dari controller
              { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
              { data: 'kode', name: 'kode' },
              { data: 'tanggal', name: 'tanggal' },
              { data: 'nama_barang', name: 'nama_barang' },
              { data: 'qty', name: 'qty' },
              { data: 'satuan', name: 'satuan' },
              { data: 'harga', name: 'harga' },
              { data: 'keterangan', name: 'keterangan' },
              { data: 'jumlah', name: 'jumlah' },
              { data: 'tempat_nama', name: 'tempat.nama' }
            ],
            dom: '<"d-flex justify-content-between align-items-center"Bf>rtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa-solid fa-download"></i> Export',
                    className: 'btn btn-success btn-sm',
                    title: "Laporan Tempat",
                    messageTop: function() {
                        const startDate = $('#monthPicker').data('daterangepicker').startDate.format('DD MMMM YYYY');
                        const endDate = $('#monthPicker').data('daterangepicker').endDate.format('DD MMMM YYYY');
                        return `Periode: ${startDate} - ${endDate}`;
                    }
                }
            ],
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            ordering: false,
            info: false,
            language: {
              emptyTable: "Silahkan pilih tempat untuk menampilkan data",
              zeroRecords: "Tidak ada data yang cocok"
            },
            footerCallback: function(row, data, start, end, display) {
              const api = this.api();
              const total = api.column(8, { search: 'applied' }).data()
                  .reduce((sum, val) => sum + parseFloat(val.replace(/[^\d]/g, '')), 0);
              
              $('#totalJumlah').html('Rp' + total.toLocaleString('id-ID'));
            },

        });

        // Reload data tabel saat filter tempat berubah
        $('#tempatFilter').change(function () {
            table.ajax.reload();
        });
      });
    </script>
</x-slot:script>
</x-layout>