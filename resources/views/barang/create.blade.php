<x-layout>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="container">
        
        <form method="POST" action="{{ route('barang.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="kode" class="form-label">Kode Barang</label>
                <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                       id="kode" name="kode" required>
                @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Barang</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="mb-3">
                <label for="satuan" class="form-label">Satuan</label>
                <select class="form-control @error('satuan') is-invalid @enderror" 
                        id="satuan" name="satuan" required>
                    <option value="">Pilih Satuan</option>
                    <option value="SAK">SAK</option>
                    <option value="BUAH">BUAH</option>
                    <option value="KG">KG</option>
                    <option value="METER">METER</option>
                    <option value="LITER">LITER</option>
                    <option value="LITER">LBR</option>
                    <option value="LITER">BTG</option>
                    <option value="LITER">KTK</option>
                </select>
                @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
    
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

</x-layout>
