@extends('layout.index')

@section('title', 'Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Barang</h4>

        {{-- Tombol Tambah Barang hanya untuk admin --}}
        @if($user && $user['role'] === 'admin')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </button>
        @endif
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga</th>
                @if($user && $user['role'] === 'admin')
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori }}</td>
                <td>{{ number_format($barang->harga, 0, ',', '.') }}</td>

                {{-- Aksi hanya muncul jika admin --}}
                @if($user && $user['role'] === 'admin')
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditBarang{{ $barang->id }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>

                    <!-- Tombol Delete -->
                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus barang ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
                @endif
            </tr>

            {{-- Modal Edit Barang khusus admin --}}
            @if($user && $user['role'] === 'admin')
            <div class="modal fade" id="modalEditBarang{{ $barang->id }}" tabindex="-1" aria-labelledby="modalEditBarangLabel{{ $barang->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditBarangLabel{{ $barang->id }}">Edit Barang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_barang{{ $barang->id }}" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang{{ $barang->id }}" name="nama_barang" value="{{ $barang->nama_barang }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori{{ $barang->id }}" class="form-label">Kategori</label>
                                    <input type="text" class="form-control" id="kategori{{ $barang->id }}" name="kategori" value="{{ $barang->kategori }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga{{ $barang->id }}" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga{{ $barang->id }}" name="harga" value="{{ $barang->harga }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @empty
            <tr>
                <td colspan="{{ $user && $user['role'] === 'admin' ? '5' : '4' }}" class="text-center">Belum ada data barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal Tambah Barang hanya untuk admin --}}
@if($user && $user['role'] === 'admin')
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('barang.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Barang</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
