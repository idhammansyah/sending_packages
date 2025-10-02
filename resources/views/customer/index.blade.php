@extends('layout.index')

@section('title', 'Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Customer</h4>
        <!-- Tombol Tambah Customer hanya admin -->
        @if($user && $user['role'] == 'admin')
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahCustomer">
            <i class="bi bi-plus-circle"></i> Tambah Customer
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
                @if($user && $user['role'] == 'admin')
                <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($customer as $index => $c)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $c->name }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->role }}</td>
                @if($user && $user['role'] == 'admin')
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditCustomer{{ $c->id }}">
                        <i class="bi bi-pencil"></i> Edit
                    </button>

                    <!-- Tombol Delete -->
                    <form action="{{ route('customer.destroy', $c->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus customer ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
                @endif
            </tr>

            <!-- Modal Edit Customer -->
            <div class="modal fade" id="modalEditCustomer{{ $c->id }}" tabindex="-1" aria-labelledby="modalEditCustomerLabel{{ $c->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('customer.update', $c->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Customer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name{{ $c->id }}" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name{{ $c->id }}" name="name" value="{{ $c->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email{{ $c->id }}" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email{{ $c->id }}" name="email" value="{{ $c->email }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password{{ $c->id }}" class="form-label">Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" id="password{{ $c->id }}" name="password">
                                </div>
                                <input type="hidden" name="role" value="customer">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data customer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Customer -->
@if($user && $user['role'] == 'admin')
<div class="modal fade" id="modalTambahCustomer" tabindex="-1" aria-labelledby="modalTambahCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('customer.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Customer</h5>
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
                    <input type="hidden" name="role" value="customer">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Customer</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
