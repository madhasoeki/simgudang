<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('barang.update', $barang->kode) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="nama">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="satuan">Satuan</label>
                                    <select class="form-control @error('satuan') is-invalid @enderror" 
                                            id="satuan" name="satuan" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="SAK" {{ old('satuan', $barang->satuan) == 'SAK' ? 'selected' : '' }}>SAK</option>
                                        <option value="BUAH" {{ old('satuan', $barang->satuan) == 'BUAH' ? 'selected' : '' }}>BUAH</option>
                                        <option value="KG" {{ old('satuan', $barang->satuan) == 'KG' ? 'selected' : '' }}>KG</option>
                                        <option value="METER" {{ old('satuan', $barang->satuan) == 'METER' ? 'selected' : '' }}>METER</option>
                                        <option value="LITER" {{ old('satuan', $barang->satuan) == 'LITER' ? 'selected' : '' }}>LITER</option>
                                        <option value="LBR" {{ old('satuan', $barang->satuan) == 'LBR' ? 'selected' : '' }}>LBR</option>
                                        <option value="BTG" {{ old('satuan', $barang->satuan) == 'BTG' ? 'selected' : '' }}>BTG</option>
                                        <option value="KTK" {{ old('satuan', $barang->satuan) == 'KTK' ? 'selected' : '' }}>KTK</option>
                                    </select>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
