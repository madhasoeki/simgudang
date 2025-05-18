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
                  <div class="d-flex justify-content-between">
                      <button class="btn btn-default" id="monthPickerMiss">
                          <i class="far fa-calendar-alt mr-2"></i>
                          <span>{{ date('F Y') }}</span>
                      </button>
                  </div>
                </div>
                <div class="card-body">
                  <table id="tabelMiss" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Miss</th>
                        <th>Keterangan</th>
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

  <script>
  $(function () {
      // --- Datepicker custom periode 26-25 ---
      function getCustomRange(date) {
          let start = date.clone().subtract(1, 'month').date(26);
          let end = date.clone().date(25);
          return { start, end };
      }
      let currentMonth = moment().format('YYYY-MM');
      const defaultDate = moment();
      const { start, end } = getCustomRange(defaultDate);
      $('#monthPickerMiss').daterangepicker({
          startDate: start,
          endDate: end,
          locale: {
              format: 'DD/MM/YYYY',
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
              'Periode Ini': [getCustomRange(moment()).start, getCustomRange(moment()).end],
              'Periode Lalu': [getCustomRange(moment().subtract(1, 'month')).start, getCustomRange(moment().subtract(1, 'month')).end],
          }
      }, function(start, end) {
          currentMonth = end.format('YYYY-MM');
          $('#monthPickerMiss span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
          if (table) table.ajax.reload();
      });
      // Initial label
      $('#monthPickerMiss span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));

      // --- DataTable ---
      let table = $('#tabelMiss').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url: "{{ route('opname.miss.data') }}",
              data: function(d) {
                  d.month = currentMonth;
              },
              error: function(xhr) {
                  console.error(xhr.responseText);
              }
          },
          columns: [
              { data: 'DT_RowIndex', orderable: false },
              { data: 'barang_kode' },
              { data: 'barang_nama' },
              { data: 'barang_satuan' },
              { data: 'selisih' },
              { data: 'keterangan' },
          ],
          dom: '<"d-flex justify-content-between align-items-center"Bf>rtip',
          buttons: [
              {
                  extend: 'excel',
                  text: '<i class="fa-solid fa-download"></i> Export',
                  className: 'btn btn-success btn-sm',
                  titleAttr: 'Export to Excel',
                  title: 'Data Miss',
                  messageTop: function() {
                      const startDate = $('#monthPickerMiss').data('daterangepicker').startDate.format('DD/MM/YYYY');
                      const endDate = $('#monthPickerMiss').data('daterangepicker').endDate.format('DD/MM/YYYY');
                      return `Periode: ${startDate} - ${endDate}`;
                  },
                  exportOptions: {
                        columns: ':not(:first-child)'
                  }
              }
          ],
          responsive: true,
          lengthChange: false,
          autoWidth: false,
          ordering: false,
          info: false,
          language: {
              emptyTable: "Tidak ada data miss"
          }
      });
  });
  </script>
</x-slot:script>

</x-layout>