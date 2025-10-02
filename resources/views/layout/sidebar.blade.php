@php
    $user = session('user');
    $role = $user->role ?? null;
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Menu untuk ADMIN --}}
        @if($role === 'admin')
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders/driver') }}">
                    <i class="bi bi-truck"></i>
                    <span>List Order Driver</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders/customer') }}">
                    <i class="bi bi-people"></i>
                    <span>List Order Customer</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/payments/unpaid') }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>List Payment Belum Dibayar</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders/status') }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Status Order</span>
                </a>
            </li>
        @endif

        @if($role === 'customer')
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders/driver') }}">
                    <i class="bi bi-truck"></i>
                    <span>List Order</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/payments/unpaid') }}">
                    <i class="bi bi-cash-stack"></i>
                    <span>Payment</span>
                </a>
            </li>
        @endif

        @if($role === 'driver')
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/orders/driver') }}">
                    <i class="bi bi-truck"></i>
                    <span>List Order</span>
                </a>
            </li>
        @endif

        {{-- Tombol Logout --}}
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link text-danger" style="border:none;background:none;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </li>
    </ul>
</aside>
