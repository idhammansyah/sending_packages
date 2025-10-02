@extends('layout.index')

@section('title', 'Menu Management')

@section('content')

{!! renderBreadcrumb() !!}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
  /* Styling tambahan untuk hirarki menu */
  .menu-list-level {
    padding-left: 0;
    /* Untuk ul paling atas */
  }

  .menu-list-level .menu-list-level {
    margin-top: 5px;
    margin-left: 20px;
    /* Indentasi untuk setiap level anak */
    border-left: 2px solid #eee;
    /* Garis vertikal indikator anak */
    padding-left: 10px;
  }

  .list-group-item.nested-menu-container {
    border: none;
    padding: 0;
    margin-bottom: 5px;
    /* Jarak antar grup anak */
  }

  .menu-item {
    border-radius: .25rem;
    margin-bottom: 2px;
    cursor: grab;
    /* Indikasi bahwa item bisa di-drag */
  }

  .menu-item:active {
    cursor: grabbing;
  }

  /* Style untuk placeholder saat drag */
  .placeholder {
    border: 1px dashed #ccc !important;
    background-color: #f7f7f7;
    height: 40px;
    margin-bottom: 2px;
  }

</style>

<div class="container-fluid">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">
        <div class="card-title mb-4 d-flex justify-content-between">
          <span>Menu Management</span>
          <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">+ Tambah
            Menu</button>
        </div>

        <div class="row">
          <div class="col-md-3">
            <h5>Kategori Menu</h5>
            <ul class="list-group" id="kategori-list">
              @foreach ($categories as $category)
              <li class="list-group-item kategori-item" data-id="{{ $category->id_menu_kategori }}">
                {{ $category->nama_kategori }}
              </li>
              @endforeach
            </ul>
          </div>

          <div class="col-md-9">
            <h5>Daftar Menu</h5>
            <div id="menu-list-container">
              <ul class="list-group menu-list-level" id="menu-list">
                @if(isset($menus) && $menus->count() > 0)
                @foreach($menus as $menu)
                <li class="list-group-item menu-item d-flex justify-content-between align-items-center"
                  data-id="{{ $menu->id_menus }}">
                  <div>
                    <i class="{{ $menu->class }}"></i>
                    <a href="{{ $menu->url_link }}">{{ $menu->nama_menu }}</a>
                  </div>
                  <div class="menu-actions">
                    <button class="btn btn-warning btn-sm btn-edits" data-id_menu="{{ $menu->id_menus }}">Edit</button>
                    <button class="btn btn-danger btn-sm btn-deletes"
                      data-id_menu="{{ $menu->id_menus}}">Delete</button>
                  </div>
                </li>
                @endforeach
                @else
                <li class="list-group-item text-center text-muted">Pilih kategori untuk menampilkan menu.</li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- jauhin dulu ini --}}
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

<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formAddMenu" method="POST" action="{{ route('menu.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addMenuLabel">Tambah Menu Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
          </div>
          <div class="mb-3">
            <label for="url_link" class="form-label">URL Link</label>
            <input type="text" class="form-control" id="url_link" name="url_link" required>
          </div>
          <div class="mb-3">
            <label for="class_icon" class="form-label">Class Icon (Opsional, ex: bi bi-house)</label>
            <input type="text" class="form-control" id="class_icon" name="class">
          </div>
          <div class="mb-3">
            <label for="id_menu_kategori" class="form-label">Kategori Menu</label>
            <select class="form-control" id="id_menu_kategori" name="id_menu_kategori" required>
              @foreach($categories as $category)
              <option value="{{ $category->id_menu_kategori }}">{{ $category->nama_kategori }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="id_modules" class="form-label">Module Terkait</label>
            <select class="form-control" id="id_modules" name="id_modules" required>
              {{-- Opsi module akan dimuat di sini, pastikan variabel $modules tersedia dari controller --}}
              @foreach($modules as $module)
              <option value="{{ $module->id_modules }}">{{ $module->nama_modules }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="posisi" class="form-label">Posisi Menu</label>
            <select class="form-control" id="posisi" name="posisi" required>
              <option value="sidebar">Sidebar</option>
              <option value="navbar">Navbar</option>
              {{-- Tambah posisi lain jika ada --}}
            </select>
          </div>
          <div class="mb-3">
            <label for="id_parent" class="form-label">Parent Menu (Kosongkan jika Main Menu)</label>
            <select class="form-control" id="id_parent" name="id_parent">
              <option value="0">-- Main Menu --</option>
              {{-- Opsi menu parent akan dimuat di sini jika diperlukan, bisa dari $all_menus --}}
              @foreach($all_menus as $menu)
              {{-- Tampilkan hanya menu yang id_parent = 0 sebagai calon parent --}}
              @if($menu->id_parent == 0)
              <option value="{{ $menu->id_menus }}">{{ $menu->nama_menu }}</option>
              @endif
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="urutan" class="form-label">Urutan</label>
            <input type="number" class="form-control" id="urutan" name="urutan" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-labelledby="deleteMenuLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formDeleteMenu" method="POST">
        @csrf
        @method('POST') {{-- Metode POST digunakan untuk rute Laravel --}}
        <div class="modal-header">
          <h5 class="modal-title" id="deleteMenuLabel">Konfirmasi Hapus Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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


@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Tambahkan jQuery UI dan NestedSortable CDN --}}
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestedSortable/2.0.0/jquery.ui.nestedSortable.js">
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">


<script type="text/javascript">
  $(document).ready(function () {

    // Fungsi untuk menginisialisasi NestedSortable pada daftar menu
    function initializeSortable() {
      $('#menu-list').nestedSortable({
        handle: 'div', // Elemen yang bisa di-drag
        items: 'li', // Item yang bisa diurutkan
        toleranceElement: '> div', // Area toleransi untuk drag
        listType: 'ul', // Tipe list yang digunakan (ul)
        maxLevels: 3, // Batasan level kedalaman (sesuaikan kebutuhan)
        opacity: .6,
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        tabSize: 20, // Jarak indentasi untuk nested
        expandOnHover: 700,
        isTree: true,
        startCollapsed: true, // Mulai dalam keadaan collapsed
        // Callback setelah item di-drop
        stop: function (event, ui) {
          // Dapatkan struktur baru dari menu
          let serialized = $('#menu-list').nestedSortable('serialize');

          // Tampilkan loading spinner
          $('#loadingSpinner').show();

          // Kirim data ke backend via AJAX
          $.ajax({
            url: '/menu/reorder', // Route baru untuk menyimpan urutan
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}', // Laravel CSRF token
              menu_data: serialized
            },
            success: function (response) {
              if (response.status === 'success') {
                // Tampilkan pesan sukses
                alert(response.message);
                // Opsional: Muat ulang menu jika diperlukan, atau cukup biarkan
                // tampilan tetap (karena sudah diupdate oleh drag-n-drop)
              } else {
                alert('Gagal menyimpan urutan: ' + response.message);
              }
            },
            error: function (xhr, status, error) {
              alert('Terjadi kesalahan saat menyimpan urutan: ' + error);
              console.error("AJAX Error: ", xhr.responseText);
            },
            complete: function () {
              $('#loadingSpinner').hide(); // Sembunyikan spinner
            }
          });
        }
      });
    }

    // Fungsi untuk merender menu secara rekursif (parent-child)
    function renderMenus(menusArray) {
      let html = '';
      if (menusArray.length > 0) {
        html += '<ul class="list-group menu-list-level">'; // Start a new list for each level
        menusArray.forEach(function (menu) {
          html += `<li class="list-group-item menu-item d-flex justify-content-between align-items-center" data-id="${menu.id_menus}">
                            <div>
                                <i class="${menu.class}"></i>
                                <a href="${menu.url_link}">${menu.nama_menu}</a>
                            </div>
                            <div class="menu-actions">
                                <button class="btn btn-warning btn-sm btn-edits" data-id_menu="${menu.id_menus}">Edit</button>
                                <button class="btn btn-danger btn-sm btn-deletes" data-id_menu="${menu.id_menus}">Delete</button>
                            </div>
                        </li>`;
          // Jika menu ini punya anak, panggil fungsi ini lagi secara rekursif
          if (menu.children && menu.children.length > 0) {
            html += `<li class="list-group-item nested-menu-container">`; // Wadah untuk sub-menu
            html += renderMenus(menu.children); // Panggil rekursif
            html += `</li>`;
          }
        });
        html += '</ul>'; // Tutup list
      } else {
        html =
          '<ul class="list-group"><li class="list-group-item text-center text-muted">Tidak ada menu yang ditemukan.</li></ul>';
      }
      return html;
    }


    $('.kategori-item').on('click', function () {
      const categoryId = $(this).data('id');
      $('.kategori-item').removeClass('active');
      $(this).addClass('active');

      // $('#loadingSpinner').show(); // Tampilkan spinner (pastikan elemen ini ada)

      $.get(`/menu/get-by-category/${categoryId}`, function (data) {
        let htmlContent = '';
        if (data.length > 0) {
          htmlContent = renderMenus(data);
        } else {
          htmlContent =
            '<ul class="list-group"><li class="list-group-item text-center text-muted">Tidak ada menu untuk kategori ini.</li></ul>';
        }
        $('#menu-list-container').html(htmlContent); // Update konten di dalam container

        // PENTING: Inisialisasi ulang sortable setiap kali menu dimuat ulang
        initializeSortable();

      }).always(function () {
        $('#loadingSpinner').hide(); // Sembunyikan spinner
      });
    });

    // Event listener untuk tombol edit
    // Perhatikan: Karena menu dimuat via AJAX, event listener ini harus diletakkan pada
    // elemen induk yang statis dan menggunakan delegasi event.
    $(document).on('click', '.btn-edits', function () {
      let id_menu = $(this).data('id_menu');

      $('#loadingSpinner').show();

      $.get(`/menu/${id_menu}`, function (data) {
        $('#edit_id').val(data.id_menus);
        $('#edit_nama_menu').val(data.nama_menu);
        $('#edit_url_link').val(data.url_link);
        $('#edit_class').val(data.class);
        $('#edit_urutan').val(data.urutan);
        $('#edit_id_parent').val(data.id_parent || 0); // Set value untuk parent
        $('#formEditMenu').attr('action', `/menu/${id_menu}`);

        // Opsional: Muat ulang opsi parent menu di modal edit
        // Jika Anda ingin menu parent yang bisa dipilih hanya dari kategori yang aktif
        // Anda bisa panggil AJAX lain di sini untuk mengisi #edit_id_parent

        $('#editMenuModal').modal('show');
      }).always(function () {
        $('#loadingSpinner').hide();
      });
    });

    // Event listener untuk tombol delete
    $(document).on('click', '.btn-deletes', function () {
      const id_menu = $(this).data('id_menu');

      $('#loadingSpinner').show();

      setTimeout(function () { // Timeout hanya untuk simulasi loading, bisa dihapus
        $('#formDeleteMenu').attr('action', `/menu/${id_menu}/delete`);
        $('#deleteMenuModal').modal('show');
        $('#loadingSpinner').hide();
      }, 300); // Simulasi waktu loading
    });

    if ($('#menu-list').children().length > 0) {
      initializeSortable();
    }
  });

</script>

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
