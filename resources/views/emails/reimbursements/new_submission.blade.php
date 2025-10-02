<x-mail::message>
# Pengajuan Reimbursement Baru

Pengajuan reimbursement baru telah diajukan oleh **{{ $user->full_name  ?? 'Pengguna Tidak Diketahui' }}**.

**Detail Pengajuan:**
- **Judul:** {{ $reimbursement->title }}
- **Kategori:** {{ $category->category_name ?? 'Tidak Diketahui' }}
- **Jumlah:** Rp{{ number_format($reimbursement->amount, 0, ',', '.') }}
- **Deskripsi:** {{ $reimbursement->description }}
- **Diajukan Pada:** {{ $reimbursement->submitted_at}}

{{-- Tampilkan Bukti Transaksi jika ada --}}
@if ($reimbursement->bukti_transaksi)
**Bukti Transaksi:**
<br>
{{-- Menggunakan $message->embed untuk menyematkan gambar langsung ke email --}}
<img src="{{ $message->embed(storage_path('app/public/' . $reimbursement->bukti_transaksi)) }}" alt="Bukti Transaksi" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
<br>
{{-- Juga berikan tombol untuk mengunduh, jaga-jaga jika gambar tidak tampil --}}
<x-mail::button :url="Storage::url($reimbursement->bukti_transaksi)">
Unduh Bukti Transaksi
</x-mail::button>
@endif

Anda dapat meninjau pengajuan ini melalui dashboard.

<x-mail::button :url="route('reimbursement-menu')">
Lihat Pengajuan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
