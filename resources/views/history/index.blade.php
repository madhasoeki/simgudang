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
                                <tbody>
                                    @foreach($histories as $history)
                                        <tr>
                                            <td>{{ $loop->iteration + ($histories->currentPage() - 1) * $histories->perPage() }}</td>
                                            <td>{{ $history->created_at->setTimezone('Asia/Jakarta')->format('d-m-Y') }}</td>
                                            <td>{{ $history->created_at->setTimezone('Asia/Jakarta')->format('H:i:s') }}</td>
                                            <td>{{ $history->user ? $history->user->name : '-' }}</td>
                                            <td>{{ $history->table_name }}</td>
                                            <td>{{ $history->record_id }}</td>
                                            <td>{{ $history->action }}</td>
                                            <td>
                                                @php $ov = $history->old_values ?? []; @endphp
                                                @if(!empty($ov) && is_array($ov))
                                                    @foreach($ov as $key => $val)
                                                        <b>{{ ucfirst($key) }}</b>: {{ is_array($val) ? json_encode($val) : $val }}<br>
                                                    @endforeach
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php $nv = $history->new_values ?? []; @endphp
                                                @if(!empty($nv) && is_array($nv))
                                                    @foreach($nv as $key => $val)
                                                        <b>{{ ucfirst($key) }}</b>: {{ is_array($val) ? json_encode($val) : $val }}<br>
                                                    @endforeach
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="card-footer clearfix">
                                {{ $histories->links() }}
                            </div>
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
                        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                        '2 Bulan Lalu': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
                        '3 Bulan Lalu': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
                    }
                }, function(start, end) {
                    currentMonth = start.format('YYYY-MM');
                    $('#monthPickerOpname span').html(start.format('MMMM YYYY')); // <-- Tambahan ini
                    table.ajax.reload();
                });
  
                // Handle initial render
                const initialStart = picker.data('daterangepicker').startDate;
                $('#monthPickerOpname span').html(initialStart.format('MMMM YYYY'));
                
                return picker;
            };
  
            // Inisialisasi datepicker pertama kali
            let currentMonth = moment().format('YYYY-MM');
            const picker = initDatePicker();
  
            // Inisialisasi DataTable
            const table = $('#tabel-opname').DataTable({
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
                        className: 'btn btn-success btn-sm', // Ubah style tombol
                        title: "REKAPAN STOCK OPNAME",
                        messageTop: function() {
                            // Ambil periode dari datepicker
                            const startDate = $('#monthPickerOpname').data('daterangepicker').startDate.format('DD/MM/YYYY') ;
                            const endDate = $('#monthPickerOpname').data('daterangepicker').endDate.format('DD/MM/YYYY') ;
                            return `Periode: ${startDate} - ${endDate}`;
                        },
                        exportOptions: {
                            columns: ':not(:first-child)' // Kecualikan kolom pertama (Nomor)
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