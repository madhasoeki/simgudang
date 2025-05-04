<x-layout>
    <x-slot:title>Tambah Transaksi Masuk</x-slot:title>

    <x-slot:style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Transaksi Masuk</h3>
                        </div>
                        <form action="{{ route('transaksi-masuk.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" 
                                           name="tanggal" 
                                           class="form-control @error('tanggal') is-invalid @enderror"
                                           value="{{ old('tanggal', now()->format('Y-m-d')) }}">
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="barang_kode" 
                                            class="form-control select2 @error('barang_kode') is-invalid @enderror">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barang as $item)
                                            <option value="{{ $item->kode }}" {{ old('barang_kode') == $item->kode ? 'selected' : '' }}>
                                                {{ $item->kode }} - {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>QTY</label>
                                    <input type="number" 
                                           name="qty" 
                                           class="form-control @error('qty') is-invalid @enderror"
                                           value="{{ old('qty') }}">
                                    @error('qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Harga Satuan</label>
                                    <input type="number" 
                                           name="harga" 
                                           class="form-control @error('harga') is-invalid @enderror"
                                           value="{{ old('harga') }}">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-default">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-slot:script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
        <script>
            $(function () {
                $('.select2').select2({
                    theme: 'bootstrap4'
                });
            });
        </script>
    </x-slot:script>
</x-layout>