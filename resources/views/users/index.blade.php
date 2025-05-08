<!-- filepath: c:\laragon\www\simgudang-bootstrap\resources\views\users\index.blade.php -->
<x-layout>

    <x-slot:title>Kelola User</x-slot:title>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-end">
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah User
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="tabel-users" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;">No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ auth()->user()->name }}</td>
                                        <td>{{ auth()->user()->email }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', auth()->user()->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @foreach($users as $index => $user)
                                        <tr>
                                            <td>{{ $index + 2 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                @if(!$user->hasRole('super-admin'))
                                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $user->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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
        <script>
            $(document).ready(function () {
                const table = $('#tabel-users').DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    ordering: false,
                    searching: false,
                    info: false,
                    dom: '<"row"<"col-sm-12 col-md-6"f>>rtip',
                });

                // SweetAlert for delete confirmation
                $('.btn-delete').on('click', function () {
                    const userId = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus user ini?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/users/${userId}`,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'User berhasil dihapus!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(() => location.reload(), 1500);
                                },
                                error: function () {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal menghapus user!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </x-slot:script>

</x-layout>