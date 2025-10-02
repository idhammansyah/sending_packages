@extends('layout.index')

@section('title', 'Driver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Driver</h4>

        <!-- Tombol Tambah Driver hanya untuk Admin -->
        @if($user['role'] == 'admin')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahDriver">
            <i class="bi bi-plus-circle"></i> Tambah Driver
        </button>
        @endif
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                @if($user['role'] == 'admin')
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($driver as $index => $driver)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->email }}</td>
                <td>{{ $driver->role }}</td>

                @if($user['role'] == 'admin')
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditDriver{{ $driver->id }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>

                    <!-- Tombol Delete -->
                    <form action="{{ route('driver.destroy', $driver->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus driver ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
                @endif
            </tr>

            <!-- Modal Edit Driver (hanya admin yang bisa akses modal ini juga) -->
            @if($user['role'] == 'admin')
            <div class="modal fade" id="modalEditDriver{{ $driver->id }}" tabindex="-1" aria-labelledby="modalEditDriverLabel{{ $driver->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('driver.update', $driver->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditDriverLabel{{ $driver->id }}">Edit Driver</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name{{ $driver->id }}" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name{{ $driver->id }}" name="name" value="{{ $driver->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email{{ $driver->id }}" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email{{ $driver->id }}" name="email" value="{{ $driver->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password{{ $driver->id }}" class="form-label">Password (isi jika ingin ubah)</label>
                                    <input type="password" class="form-control" id="password{{ $driver->id }}" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="role{{ $driver->id }}" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role{{ $driver->id }}" name="role" value="{{ $driver->role }}" readonly>
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
                <td colspan="{{ $user['role'] == 'admin' ? '5' : '4' }}" class="text-center">Belum ada data driver.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Driver (hanya admin yang bisa akses modal ini juga) -->
@if($user['role'] == 'admin')
<div class="modal fade" id="modalTambahDriver" tabindex="-1" aria-labelledby="modalTambahDriverLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('driver.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDriverLabel">Tambah Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" name="role" value="driver" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Driver</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('scripts')
<!-- optional JS -->
@endsection
