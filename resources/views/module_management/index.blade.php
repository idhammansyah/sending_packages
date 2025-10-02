@extends('layout.index')
@section('title', 'Master Module')

@section('content')
{!! renderBreadcrumb() !!}
<div class="container mt-4">
  <h4 class="mb-3">Master Module</h4>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- FORM TAMBAH MODULE --}}
  <form action="{{ route('modules.store') }}" method="POST" class="card p-3 mb-4">
    @csrf
    <div class="row">
      <div class="col-md-4 mb-2">
        <label>Nama Module</label>
        <input type="text" name="nama_modules" class="form-control" required>
      </div>
      <div class="col-md-4 mb-2">
        <label>Judul Module</label>
        <input type="text" name="judul_modules" class="form-control" required>
      </div>
      <div class="col-md-4 mb-2">
        <label>Login Dibutuhkan?</label>
        <select name="login" class="form-select" required>
          <option value="1">Ya</option>
          <option value="0">Tidak</option>
        </select>
      </div>
    </div>
    <div class="mb-2">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="2"></textarea>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Simpan</button>
  </form>

  {{-- TABEL MODULE --}}
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Nama</th>
        <th>Judul</th>
        <th>Login</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($modules as $mod)
      <tr>
        <td>{{ $mod->nama_modules }}</td>
        <td>{{ $mod->judul_modules }}</td>
        <td>{{ $mod->login ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $mod->deskripsi }}</td>
        <td>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $mod->id_modules }}">Edit</button>

            <form action="{{ route('modules.destroy', $mod->id_modules) }}" method="POST"
              onsubmit="return confirm('Yakin ingin hapus module ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- MODAL EDIT MODULE --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Module</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_id">
          <div class="mb-3">
            <label>Nama Module</label>
            <input type="text" name="nama_modules" id="edit_nama_modules" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Judul Module</label>
            <input type="text" name="judul_modules" id="edit_judul_modules" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Login Dibutuhkan?</label>
            <select name="login" id="edit_login" class="form-select" required>
              <option value="1">Ya</option>
              <option value="0">Tidak</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea>
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

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.btn-edit').on('click', function () {
        let id = $(this).data('id');

        $.ajax({
            url: '/edit-module/' + id,
            type: 'GET',
            success: function (data) {
                $('#edit_nama_modules').val(data.nama_modules);
                $('#edit_judul_modules').val(data.judul_modules);
                $('#edit_login').val(data.login);
                $('#edit_deskripsi').val(data.deskripsi);
                $('#formEdit').attr('action', '/update-module/' + id);
                $('#editModal').modal('show');
            },
            error: function () {
                alert('Gagal mengambil data module.');
            }
        });
    });
});
</script>
@endsection
