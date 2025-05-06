<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('projek.update', $projek->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="nama">Nama Projek</label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $projek->nama) }}" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
