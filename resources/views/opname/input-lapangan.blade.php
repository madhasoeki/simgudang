<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Input Stok Lapangan</h3>
                        </div>
                        <form action="{{ route('opname.simpan-lapangan', $opname->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Kode Barang</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $opname->barang_kode }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $opname->barang->nama }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Stok Sistem</label>
                                    <input type="number" class="form-control" 
                                           value="{{ $opname->stock_total }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <label>Stok Lapangan *</label>
                                    <input type="number" name="total_lapangan" 
                                           class="form-control @error('total_lapangan') is-invalid @enderror" 
                                           value="{{ old('total_lapangan', $opname->total_lapangan) }}" 
                                           required>
                                    @error('total_lapangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Keterangan (Opsional)</label>
                                    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $opname->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('opname.index') }}" class="btn btn-default">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>