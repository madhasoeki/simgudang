<x-layout>
    <x-slot:title>Edit Transaksi Keluar</x-slot:title>

    <x-slot:style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Transaksi Keluar</h3>
                        </div>
                        <form action="{{ route('transaksi-keluar.update', $transaksi->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $transaksi->tanggal->format('Y-m-d')) }}">
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Tempat</label>
                                    <select name="tempat_id" class="form-control select2 @error('tempat_id') is-invalid @enderror">
                                        <option value="">Pilih Tempat</option>
                                        @foreach($tempat as $item)
                                            <option value="{{ $item->id }}" {{ old('tempat_id', $transaksi->tempat_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('tempat_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="barang_kode" class="form-control select2 @error('barang_kode') is-invalid @enderror">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barang as $item)
                                            <option value="{{ $item->kode }}" {{ old('barang_kode', $transaksi->barang_kode) == $item->kode ? 'selected' : '' }}>{{ $item->kode }} - {{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('barang_kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>QTY</label>
                                            <input type="number" name="qty" class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty', $transaksi->qty) }}">
                                            @error('qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $transaksi->harga) }}">
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-default">Batal</a>
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
