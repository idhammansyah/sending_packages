@extends('layout.index')

@section('title', 'User Roles')

@section('content')

{!! renderBreadcrumb() !!}

<section class="dashboard">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">

          <h5 class="card-title">Master Roles</h5>

          <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal"
            data-bs-target="#addRoleModal">
            <i class="bi bi-plus"></i>&nbsp; Add New Roles
          </button>

          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Role Name</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $index => $role)
              <tr id="row-role-{{ $role->id_roles }}">
                <th scope="row">{{ $index + 1 }}</th>
                <td class="role-name">{{ $role->nama_roles }}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-warning edit-role-btn" data-id="{{ $role->id_roles }}"
                    data-name="{{ $role->nama_roles }}">
                    Edit
                  </button>
                  <button type="button" class="btn btn-sm btn-danger delete-role-btn" data-id="{{ $role->id_roles }}"
                    data-name="{{ $role->nama_roles }}">
                    Delete
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Add -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addRoleModalLabel">Add Roles</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Add New Role</label>
            <input type="text" class="form-control" name="role_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editRoleForm">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <input type="hidden" id="edit_role_id">
          <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" class="form-control" id="edit_role_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
    </form>
  </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Role</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Yakin ingin hapus role <strong id="delete_role_name"></strong>?</p>
        <input type="hidden" id="delete_role_id">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  $(document).ready(function () {
    // Edit
    $('.edit-role-btn').on('click', function () {
      $('#edit_role_id').val($(this).data('id'));
      $('#edit_role_name').val($(this).data('name'));
      $('#editRoleModal').modal('show');
    });

    $('#editRoleForm').on('submit', function (e) {
      e.preventDefault();
      let id_roles = $('#edit_role_id').val();
      let name = $('#edit_role_name').val();
      $.ajax({
        url: `/user-roles-update/${id_roles}`,
        type: 'PUT',
        data: {
          _token: $('meta[name=\"csrf-token\"]').attr('content'),
          role_name: name
        },
        success: function () {
          $('#editRoleModal').modal('hide');
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        },
        error: function (xhr) {
          alert('Gagal update: ' + xhr.responseJSON.message);
        }
      });
    });

    // Delete
    $('.delete-role-btn').on('click', function () {
      $('#delete_role_id').val($(this).data('id'));
      $('#delete_role_name').text($(this).data('name'));
      $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function () {
      let id = $('#delete_role_id').val();
      $.ajax({
        url: `/delete-roles/${id}`,
        type: 'DELETE',
        data: {
          _token: $('meta[name=\"csrf-token\"]').attr('content')
        },
        success: function () {
          $(`#row-role-${id}`).remove();
          $('#confirmDeleteModal').modal('hide');
          setTimeout(function () {
            window.location.reload();
          }, 1000);
        },
        error: function (xhr) {
          alert('Gagal hapus: ' + xhr.responseJSON.message);
        }
      });
    });
  });

</script>
@endsection
