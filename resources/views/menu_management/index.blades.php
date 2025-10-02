@extends('layout.index')

@section('title', 'Menu Management')

@section('content')

{!! renderBreadcrumb() !!}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="container-fluid">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="card-title mb-4 d-flex justify-content-between">
          <span>Menu Management</span>
          <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMenu">Add New Menu</button>

        </div>

        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th style="width: 5%">#</th>
              <th>Nama Menu</th>
              <th>Parent</th>
              <th>Module</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($menus_raw as $menu)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $menu->nama_menu }}</td>
              <td>
                @php
                $parent = $menus_raw->firstWhere('id_menus', $menu->id_parent);
                @endphp
                {{ $parent ? $parent->nama_menu : '-' }}
              </td>
              <td>{{ $modules->firstWhere('id_modules', $menu->id_modules)?->nama_modules ?? '-' }}</td>
              <td class="text-center">
                <button type="button" class="btn btn-warning btn-sm btn-edits"
                  data-id_menu="{{ $menu->id_menus }}">Edit</button>
                <button type="button" class="btn btn-danger btn-sm btn-deletes"
                  data-id_menu="{{ $menu->id_menus }}">Delete</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>

  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="card-title mb-4 d-flex justify-content-between">
          <span>Menu Management Assign</span>
          <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal">+ Assign
            Akses</button>
        </div>

        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>Role</th>
              <th>Module</th>
              <th>Menu</th>
              <th>Kategori</th>
              <th>Read</th>
              <th>Create</th>
              <th>Update</th>
              <th>Delete</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($menus as $menu)
            <tr>
              <form action="{{ route('menu.management.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id_role" value="{{ $menu->id_roles }}">
                <input type="hidden" name="id_module" value="{{ $menu->id_modules }}">
                <td>{{ $menu->nama_roles }}</td>
                <td>{{ $menu->nama_modules }}</td>
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ $menu->nama_kategori }}</td>
                <td><input type="checkbox" name="access[]" value="read" {{ $menu->can_read ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="access[]" value="create" {{ $menu->can_create ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="access[]" value="update" {{ $menu->can_update ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="access[]" value="delete" {{ $menu->can_delete ? 'checked' : '' }}></td>
                <td><button type="submit" class="btn btn-sm btn-primary">Simpan</button></td>
              </form>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div>

  <div id="loadingSpinner"
    style="display: none; position: fixed; top: 0; left: 0; width:100%; height:100%; background-color: rgba(255,255,255,0.7); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>

</div>

{{-- MODAL ASSIGN --}}
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalLabel">Assign Role ke Menu & Module</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form action="{{ route('menu.management.assign.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label>Role</label>
            <select class="form-select" name="id_role" required>
              <option value="">-- Pilih Role --</option>
              @foreach($roles as $role)
              <option value="{{ $role->id_roles }}">{{ $role->nama_roles }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Module</label>
            <select class="form-select" name="id_module" required>
              <option value="">-- Pilih Module --</option>
              @foreach($modules as $module)
              <option value="{{ $module->id_modules }}">{{ $module->nama_modules }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Menu</label>
            <div class="form-check">
              @foreach($menus_raw as $menu)
              <input class="form-check-input" type="checkbox" name="id_menus[]" value="{{ $menu->id_menus }}"
                id="menu_{{ $menu->id_menus }}">
              <label class="form-check-label me-3" for="menu_{{ $menu->id_menus }}">{{ $menu->nama_menu }}</label><br>
              @endforeach
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Assign</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL ADD MENU --}}
<div class="modal fade" id="addMenu" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form action="{{ route('menu.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assignModalLabel">Tambah Menu Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Nama Menu</label>
              <input type="text" name="nama_menu" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Parent Menu</label>
              <select name="id_parent" class="form-select">
                <option value="0">Tidak ada</option>
                @foreach($menus_raw as $menu)
                <option value="{{ $menu->id_menus }}">{{ $menu->nama_menu }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Module</label>
              <select name="id_modules" class="form-select" required>
                <option value="">Pilih Module</option>
                @foreach($modules as $module)
                <option value="{{ $module->id_modules }}">{{ $module->nama_modules }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>URL</label>
              <input type="text" name="url_link" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label>Class Icon</label>
              <input type="text" name="class" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label>Urutan</label>
              <input type="number" name="urutan" class="form-control" value="1" required>
            </div>
            <div class="col-md-3 mb-3">
              <label>Posisi</label>
              <select name="posisi" class="form-select">
                <option value="sidebar">Sidebar</option>
                <option value="navbar">Navbar</option>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label>Status</label>
              <select name="is_active" class="form-select">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Menu</button>
        </div>
      </div>
    </form>
  </div>
</div>


{{-- MODAL EDIT MENU --}}
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <form id="formEditMenu" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <input type="hidden" name="id_menus" id="edit_id">
          <div class="mb-3">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" id="edit_nama_menu" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>URL</label>
            <input type="text" name="url_link" id="edit_url_link" class="form-control">
          </div>
          <div class="mb-3">
            <label>Class</label>
            <input type="text" name="class" id="edit_class" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL DELETE MENU --}}
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formDeleteMenu" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title">Hapus Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus menu ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('.btn-edits').on('click', function () {
      let id_menu = $(this).data('id_menu');

      $('#loadingSpinner').show(); // SHOW SPINNER

      $.get(`/menu/${id_menu}`, function (data) {
        $('#edit_id').val(data.id_menus);
        $('#edit_nama_menu').val(data.nama_menu);
        $('#edit_url_link').val(data.url_link);
        $('#edit_class').val(data.class);
        $('#formEditMenu').attr('action', `/menu/${id_menu}`);

        $('#editMenuModal').modal('show');
      }).always(function () {
        $('#loadingSpinner').hide(); // HIDE SPINNER
      });
    });

    $('.btn-deletes').on('click', function () {
      const id_menu = $(this).data('id_menu');

      $('#loadingSpinner').show(); // SHOW SPINNER

      setTimeout(function () {
        $('#formDeleteMenu').attr('action', `/menu/${id_menu}`);
        $('#deleteMenuModal').modal('show');
        $('#loadingSpinner').hide(); // HIDE SPINNER
      }, 200); // Simulasi delay biar spinner kelihatan
    });

  });

</script>
@endsection
