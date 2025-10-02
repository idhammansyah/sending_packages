@extends('layout.index')

@section('title', 'Reimbursement')

@section('content')

{!! renderBreadcrumb() !!}
{!! flash_message() !!}

<section class="dashboard">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <div class="card-title mb-4 d-flex justify-content-between">
            <span>Reimbursement</span>
            @if(isset(Auth::user()->role_id) && in_array(Auth::user()->role_id, [1, 3]))
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addReimbursementModal">
              Ajukan Reimbursement Baru
            </button>
            @endif
          </div>

          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Submitted At</th>
                  <th>Created By</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($reimbursements as $reimbursement)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $reimbursement->title }}</td>
                  <td>{{ $reimbursement->category_name }}</td>
                  <td>{{ number_format($reimbursement->amount, 2) }}</td>
                  <td>{{ $reimbursement->status_reimburse }}</td>
                  <td>{{ $reimbursement->submitted_at }}</td>
                  <td>{{ $reimbursement->full_name }}</td>
                  <td>
                    <button type="button" class="btn btn-info btn-sm btn-detail"
                      data-id="{{ $reimbursement->id }}">Detail</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

{{-- Modal untuk Form Pengajuan Reimbursement --}}
<div class="modal fade" id="addReimbursementModal" tabindex="-1" aria-labelledby="addReimbursementModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReimbursementModalLabel">Form Pengajuan Reimbursement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('reimbursements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="title" class="form-label">Judul Pengeluaran <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
            <select class="form-select" id="category_id" name="category_id" required>
              <option value="">Pilih Kategori</option>
              @foreach($reimburse_category as $category)
              <option value="{{ $category->id }}">
                {{ $category->category_name }} (Limit:{{ number_format($category->limit_per_month, 0) }})
              </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="amount" class="form-label">Jumlah (IDR) <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="attachment" class="form-label">Bukti Transaksi (PDF/JPG, max 2MB) <span
                class="text-danger">*</span></label>
            <input class="form-control" type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
              required>
            <div class="form-text">Ukuran file maksimal 2MB. Format yang diizinkan: PDF, JPG, JPEG, PNG.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Ajukan Reimbursement</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">Detail Reimbursement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr>
            <th>Judul</th>
            <td id="detail-title"></td>
          </tr>
          <tr>
            <th>Kategori</th>
            <td id="detail-kategori"></td>
          </tr>
          <tr>
            <th>Jumlah</th>
            <td id="detail-amount"></td>
          </tr>
          <tr>
            <th>Status</th>
            <td id="detail-status"></td>
          </tr>
          <tr>
            <th>Deskripsi</th>
            <td id="detail-desc"></td>
          </tr>
          <tr>
            <th>Diajukan</th>
            <td id="detail-date"></td>
          </tr>
          <tr>
            <th>Pengaju</th>
            <td id="detail-user"></td>
          </tr>
          <tr>
            <th>Bukti Transaksi</th>
            <td id="detail-bukti"></td>
          </tr>
        </table>

        @php $roleId = Auth::user()->role_id; @endphp

        {{-- Tombol APPROVE / REJECT untuk role 1 & 2 --}}
        @if(in_array($roleId, [1, 2]))
        <div class="mt-3 text-end" id="modal-approver-buttons">
          <form id="formApprove" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
          </form>
          <form id="formReject" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
          </form>
        </div>
        @endif

        {{-- Tombol EDIT / DELETE untuk role 1 & 3 --}}
        @if(in_array($roleId, [1, 3]))
        <div class="mt-3 text-end" id="modal-editor-buttons">
          <a href="#" id="btnEdit" class="btn btn-warning">Edit</a>
          <form id="formDelete" method="POST" class="d-inline"
            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
        @endif

      </div>
    </div>
  </div>
</div>


@endsection

{{-- Bagian untuk script Javascript (jika ada) --}}
@section('scripts')
{{-- Anda bisa menambahkan script untuk validasi client-side di sini,
     misalnya menggunakan JS untuk mengecek ukuran file sebelum submit --}}
<script>
  document.getElementById('attachment').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const fileSize = file.size / 1024 / 1024; // in MB
      if (fileSize > 2) {
        alert('Ukuran file melebihi batas maksimal 2MB. Mohon pilih file lain.');
        this.value = ''; // Clear the file input
      }
    }
  });

  $(document).on('click', '.btn-detail', function () {
    let id = $(this).data('id');

    $.get('/reimbursements/' + id, function (data) {
      $('#detail-title').text(data.title);
      $('#detail-kategori').text(data.category_name);
      $('#detail-amount').text(new Intl.NumberFormat().format(data.amount));
      $('#detail-status').text(data.status_reimburse);
      $('#detail-desc').text(data.description || '-');
      $('#detail-date').text(data.submitted_at);
      $('#detail-user').text(data.full_name);

      // Preview file
      const filePath = data.bukti_transaksi;
      let html = '<span class="text-muted">Tidak ada</span>';

      if (filePath) {
        const ext = filePath.split('.').pop().toLowerCase();
        const url = '/storage/' + filePath;

        if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext)) {
          html = `<img src="${url}" class="img-fluid rounded border" style="max-height:300px;">`;
        } else if (ext === 'pdf') {
          html = `<a href="${url}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File (PDF)</a>`;
        } else {
          html = `<a href="${url}" target="_blank" class="btn btn-sm btn-secondary">Download File</a>`;
        }
      }

      $('#detail-bukti').html(html);

      // Inject URL untuk form dan tombol (dinamis per ID)
      $('#formApprove').attr('action', `/reimbursements/${id}/approve`);
      $('#formReject').attr('action', `/reimbursements/${id}/reject`);
      $('#btnEdit').attr('href', `/reimbursements/${id}/edit`);
      $('#formDelete').attr('action', `/reimbursements/${id}/delete`);

      // Show modal
      $('#detailModal').modal('show');
    }).fail(function () {
      alert('Gagal mengambil detail data.');
    });
  });

</script>
@endsection
