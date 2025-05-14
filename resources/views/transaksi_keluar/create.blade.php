<x-layout>
    <x-slot:title>Input Barang Keluar</x-slot:title>

    <x-slot:style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot:style>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Barang Keluar</h3>
                        </div>
                        <form action="{{ route('transaksi-keluar.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" 
                                           class="form-control @error('tanggal') is-invalid @enderror"
                                           value="{{ old('tanggal', now()->format('Y-m-d')) }}">
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Projek</label>
                                    <select name="projek_id" class="form-control select2 @error('projek_id') is-invalid @enderror">
                                        <option value="">Pilih Projek</option>
                                        @foreach($projek as $item)
                                            <option value="{{ $item->id }}" {{ old('projek_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('projek_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="barang_kode" class="form-control select2 @error('barang_kode') is-invalid @enderror">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barang as $item)
                                            <option value="{{ $item->kode }}" {{ old('barang_kode') == $item->kode ? 'selected' : '' }}>
                                                {{ $item->kode }} - {{ $item->nama }} (Stok: {{ $item->stok->jumlah ?? 0 }})
                                            </option>
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
                                            <input type="number" name="qty" 
                                                   class="form-control @error('qty') is-invalid @enderror"
                                                   value="{{ old('qty') }}">
                                            @error('qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <select name="harga" id="harga-select"
                                                    class="form-control @error('harga') is-invalid @enderror">
                                                <option value="">Pilih Harga</option>
                                                {{-- Harga akan diisi via JS --}}
                                            </select>
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-default">
                                    Batal
                                </a>
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
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap4'
                });

                // Saat barang dipilih, ambil harga dari server
                $('select[name="barang_kode"]').on('change', function() {
                    var barangKode = $(this).val();
                    var hargaSelect = $('#harga-select');
                    hargaSelect.html('<option value="">Memuat harga...</option>');
                    if (barangKode) {
                        $.get(
                            // URL endpoint untuk mendapatkan harga berdasarkan barang
                            '{{ route('transaksi-keluar.harga-barang') }}?barang_kode=' + barangKode,
                            function(data) {
                                hargaSelect.empty();
                                if (data.length > 0) {
                                    $.each(data, function(key, value) {
                                        var formatted = 'Rp' + parseInt(value).toLocaleString('id-ID');
                                        hargaSelect.append('<option value="' + value + '">' + formatted + '</option>');
                                    });
                                    // Pilih harga tertinggi secara otomatis
                                    hargaSelect.val(Math.max.apply(Math, data));
                                } else {
                                    hargaSelect.html('<option value="">Harga tidak tersedia</option>');
                                }
                            }
                        );
                    } else {
                        hargaSelect.html('<option value="">Pilih Harga</option>');
                    }
                });
            });
        </script>
    </x-slot:script>
</x-layout>