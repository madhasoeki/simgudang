<x-layout>
  <x-slot:title>{{ $title }}</x-slot:title>
  
  <x-slot:style>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
      <style>
          .diff-negative { background-color: #ffe6e6; }
          .diff-positive { background-color: #e6ffe6; }
          .editable-cell { min-width: 100px; }
      </style>
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
                          <table id="tabel-opname" class="table table-bordered table-hover">
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
        $(function () {
          // 1. Hapus jQuery 3.6.0 yang duplikat (sudah ada di vendor/jquery)
          // 2. Perbaiki inisialisasi datepicker
          const initDatePicker = () => {
              const defaultDate = moment().startOf('month');
              const picker = $('#monthPickerOpname').daterangepicker({
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
                      'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                  }
              }, function(start, end) {
                  currentMonth = start.format('YYYY-MM');
                  $('#monthPickerOpname span').html(start.format('MMMM YYYY')); // <-- Tambahan ini
                  table.ajax.reload();
              });

              // 3. Handle initial render
              const initialStart = picker.data('daterangepicker').startDate;
              $('#monthPickerOpname span').html(initialStart.format('MMMM YYYY'));
              
              return picker;
          };

          // 4. Inisialisasi datepicker pertama kali
          let currentMonth = moment().format('YYYY-MM');
          const picker = initDatePicker();

          // 5. Inisialisasi DataTable
          const table = $('#tabel-opname').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "/stock-opname/data",
                  data: function(d) {
                      d.month = currentMonth;
                  },
                  error: function(xhr) {
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
                      { data: 'total_lapangan'},
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
                            return `
                                <div class="btn-action-group">
                                    ${!row.approved ? `
                                    <a href="/stock-opname/${data}/input-lapangan" 
                                      class="btn btn-sm btn-info" 
                                      title="Input Data Lapangan">
                                        <i class="fas fa-clipboard-check"></i> Input Lapangan
                                    </a>
                                    ` : ''}
                                    
                                    ${!row.approved ? `
                                    <button class="btn btn-sm btn-success btn-approve" 
                                            data-id="${data}"
                                            title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    ` : '<span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>'}
                                </div>
                            `;
                        }
                    }
                      
                  ],
                  dom: 'Bfrtip',
                  buttons: ['excel'],
                  ordering: false,
                  searching: false,
                  paging: false,
              });
              
              $('#tabel-opname').on('click', '.btn-approve', function() {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                
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
                            url: '/stock-opname/' + id + '/approve',
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