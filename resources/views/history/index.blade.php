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
                            <table id="tabel-history" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>User</th>
                                        <th>Tabel</th>
                                        <th>ID Record</th>
                                        <th>Aksi</th>
                                        <th>Data Sebelum</th>
                                        <th>Data Sesudah</th>
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
            // Inisialisasi datepicker

            // Datepicker: default 7 hari terakhir
            let startDate = moment().subtract(6, 'days');
            let endDate = moment();
            $('#monthPickerOpname').daterangepicker({
                startDate: startDate,
                endDate: endDate,
                autoUpdateInput: true,
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
                alwaysShowCalendars: true,
                ranges: {
                    'Hari Ini': [moment(), moment()],
                    'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                    '14 Hari Terakhir': [moment().subtract(13, 'days'), moment()],
                    'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                    'Maksimal': [moment('2025-01-01'), moment()]
                }
            }, function(start, end) {
                startDate = start;
                endDate = end;
                $('#monthPickerOpname span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                table.ajax.reload();
            });

            // Set label awal
            $('#monthPickerOpname span').html(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
  

            // Inisialisasi DataTable
            const table = $('#tabel-history').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('history.data') }}",
                    data: function(d) {
                        d.start_date = startDate.format('YYYY-MM-DD');
                        d.end_date = endDate.format('YYYY-MM-DD');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false },
                    { data: 'tanggal' },
                    { data: 'jam' },
                    { data: 'user' },
                    { data: 'table_name' },
                    { data: 'record_id' },
                    { data: 'action' },
                    { data: 'old_values' },
                    { data: 'new_values' }
                ],
                dom: '<"d-flex justify-content-between align-items-center"Bf>rtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fa-solid fa-download"></i> Export',
                        className: 'btn btn-success btn-sm',
                        title: "REKAPAN HISTORY",
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
                    emptyTable: "Belum ada data history",
                    zeroRecords: "Tidak ada data yang ditemukan",
                },
                info: false,
            });

            // Handle cancel
            $('#monthPickerOpname').on('cancel.daterangepicker', function(ev, picker) {
                startDate = moment().subtract(6, 'days');
                endDate = moment();
                $(this).find('span').html(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
                table.ajax.reload();
                picker.hide();
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