@extends('layout.index')

@section('title', 'Driver')

@section('content')
<div class="container">
    <h3 class="mb-4">Order Management</h3>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mt-3">
                <!-- Button Pesanan Saya -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPesanan">
                    Lihat Pesanan
                </button>

                <!-- Button Tambah Pesanan -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPesanan">
                    Tambah Pesanan
                </button>

                <!-- Button Payment -->
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalPayment">
                    Payment
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Modal Pesanan --}}
<div class="modal fade" id="modalPesanan" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pesanan Saya</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Total</th>
                    <th>Status Payment</th>
                    <th>Status Transaksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $o)
                <tr>
                    <td>{{ $o->id_barang }}</td>
                    <td>Rp {{ number_format($o->total_pembayaran,0,',','.') }}</td>
                    <td>{{ $o->status_payment }}</td>
                    <td>{{ $o->status_transact }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah Pesanan --}}
<div class="modal fade" id="modalTambahPesanan" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('order.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pesanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                @foreach($barang as $b)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>{{ $b->nama_barang }}</h6>
                            <p>Rp {{ number_format($b->harga,0,',','.') }}</p>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="id_barang[]" value="{{ $b->id }}" data-harga="{{ $b->harga }}">
                                <label class="form-check-label">Pilih</label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr>
            <h5>Total: Rp <span id="totalHarga">0</span></h5>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Submit Pesanan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Payment --}}
<div class="modal fade" id="modalPayment" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('order.payment') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Barang</th>
                        <th>Total</th>
                        <th>Status Payment</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orders as $o)
                    @if($o->status_payment == 'siap dibayar')
                    <tr>
                        <td>
                            <input type="radio" name="order_id" value="{{ $o->id }}">
                        </td>
                        <td>{{ $o->id_barang }}</td>
                        <td>Rp {{ number_format($o->total_pembayaran,0,',','.') }}</td>
                        <td>{{ $o->status_payment }}</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Bayar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.querySelectorAll("input[name='id_barang[]']").forEach(cb => {
    cb.addEventListener("change", function() {
        let total = 0;
        document.querySelectorAll("input[name='id_barang[]']:checked").forEach(x => {
            total += parseInt(x.getAttribute("data-harga"));
        });
        document.getElementById("totalHarga").innerText = total.toLocaleString('id-ID');
    });
});
</script>
@endsection
