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
                              <button class="btn btn-default" id="monthPickerOpname">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span>{{ date('F Y') }}</span>
                              </button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <table id="tabel-opname" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Stok Awal</th>
                                        <th>Total Masuk</th>
                                        <th>Total Keluar</th>
                                        <th>Total</th>
                                        <th>Lapangan</th>
                                        <th>Selisih</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
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
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
  const isSuperAdmin = @json($isSuperAdmin);
  $(function () {
      // --- Datepicker custom periode 26-25 ---
      function getCustomRange(date) {
          // date: moment object (bulan berjalan)
          let start = date.clone().subtract(1, 'month').date(26);
          let end = date.clone().date(25);
          return { start, end };
      }
      let currentMonth = moment().format('YYYY-MM');
      const defaultDate = moment();
      const { start, end } = getCustomRange(defaultDate);
      $('#monthPickerOpname').daterangepicker({
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
          // Ambil bulan dari end (karena end = tanggal 25 bulan berjalan)
          currentMonth = end.format('YYYY-MM');
          $('#monthPickerOpname span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));
          if (table) table.ajax.reload();
      });
      // Initial label
      $('#monthPickerOpname span').html(start.format('DD MMM YYYY') + ' - ' + end.format('DD MMM YYYY'));

      // --- DataTable ---
      let table = $('#tabel-opname').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url: "{{ route('opname.data') }}",
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
              { data: 'barang.nama' },
              { data: 'stock_awal', className: 'text-right' },
              { data: 'total_masuk', className: 'text-right' },
              { data: 'total_keluar', className: 'text-right' },
              { data: 'stock_total', className: 'text-right' },
              { data: 'total_lapangan' },
              { 
                  data: 'selisih',
                  className: 'text-right',
                  render: function(data) {
                      const cls = data > 0 ? 'text-success' : 'text-danger';
                      return `<span class="${cls}">${data}</span>`;
                  }
              },
              { data: 'keterangan' },
              {
                  data: 'id',
                  className: 'text-center',
                  render: function(data, type, row) {
                      const inputFormUrl = "{{ route('opname.input-form', ':id') }}".replace(':id', data);
                      let approveBtn = '';
                      if (!row.approved && isSuperAdmin) {
                          approveBtn = `
                              <button class="btn btn-sm btn-success btn-approve" 
                                      data-id="${data}"
                                      title="Approve">
                                  <i class="fas fa-check"></i>
                              </button>
                          `;
                      } else if (row.approved) {
                          approveBtn = '<span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>';
                      }
                      return `
                          <div class="btn-action-group">
                              ${!row.approved ? `
                              <a href="${inputFormUrl}" 
                              class="btn btn-sm btn-info" 
                              title="Input Data Lapangan">
                                  <i class="fas fa-clipboard-check"></i> Input Lapangan
                              </a>
                              ` : ''}
                              ${approveBtn}
                          </div>
                      `;
                  }
              }
          ],
          dom: '<"d-flex justify-content-between align-items-center"Bf>rtip',
          buttons: [
              {
                  extend: 'excel',
                  text: '<i class="fa-solid fa-download"></i> Export',
                  className: 'btn btn-success btn-sm',
                  title: "REKAPAN STOCK OPNAME",
                  messageTop: function() {
                      const startDate = $('#monthPickerOpname').data('daterangepicker').startDate.format('DD/MM/YYYY');
                      const endDate = $('#monthPickerOpname').data('daterangepicker').endDate.format('DD/MM/YYYY');
                      return `Periode: ${startDate} - ${endDate}`;
                  },
                  exportOptions: {
                      columns: ':not(:first-child)'
                  }
              }
          ],
          ordering: false,
          language: {
              emptyTable: "Belum ada data opname",
              zeroRecords: "Tidak ada data yang ditemukan",
          },
          info: false,
      });

      // --- Approve handler ---
      $('#tabel-opname').on('click', '.btn-approve', function() {
          const id = $(this).data('id');
          Swal.fire({
              title: 'Approve Penyesuaian?',
              text: "Stok sistem akan diupdate sesuai data lapangan!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Approve!'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      url: "{{ route('opname.approve', ':id') }}".replace(':id', id),
                      method: 'POST',
                      data: {
                          _token: '{{ csrf_token() }}',
                          keterangan: result.value
                      },
                      success: function() {
                          table.ajax.reload();
                          Swal.fire('Berhasil!', 'Stok telah disesuaikan', 'success');
                      }
                  });
              }
          });
      });
  });
  </script>
    </x-slot:script>
  </x-layout>