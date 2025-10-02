@extends('layout.index')

@section('title', 'Edit Reimbursement')

@section('content')
{!! renderBreadcrumb() !!}
<div class="container">
  <h4 class="mb-4">Edit Reimbursement</h4>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('reimbursements.update', $reimbursement->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label for="title" class="form-label">Judul</label>
      <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $reimbursement->title) }}" required>
    </div>

    <div class="mb-3">
      <label for="amount" class="form-label">Jumlah</label>
      <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $reimbursement->amount) }}" required>
    </div>

    <div class="mb-3">
      <label for="id_category_reimburse" class="form-label">Kategori Reimburse</label>
      <select name="id_category_reimburse" id="id_category_reimburse" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ $cat->id == $reimbursement->id_category_reimburse ? 'selected' : '' }}>
            {{ $cat->category_name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Deskripsi</label>
      <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $reimbursement->description) }}</textarea>
    </div>

    <div class="mb-3">
      <label for="bukti_transaksi" class="form-label">Bukti Transaksi (jika ingin mengganti)</label>
      <input type="file" name="bukti_transaksi" id="bukti_transaksi" class="form-control" accept="image/*,.pdf">

      @if($reimbursement->bukti_transaksi)
        <div class="mt-2">
          <label class="form-label">File Saat Ini:</label><br>
          @php
            $ext = pathinfo($reimbursement->bukti_transaksi, PATHINFO_EXTENSION);
          @endphp

          @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
            <img src="{{ asset('storage/' . $reimbursement->bukti_transaksi) }}" class="img-fluid border rounded" style="max-height: 250px;">
          @elseif($ext === 'pdf')
            <a href="{{ asset('storage/' . $reimbursement->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File PDF</a>
          @else
            <a href="{{ asset('storage/' . $reimbursement->bukti_transaksi) }}" target="_blank" class="btn btn-sm btn-secondary">Download File</a>
          @endif
        </div>
      @endif
    </div>

    <div class="d-flex justify-content-between">
      <a href="{{ route('reimbursement-menu') }}" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>
</div>
@endsection
