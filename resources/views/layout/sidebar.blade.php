@php
$user = session('user');
$role = $user->role ?? null;
@endphp

<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Menu untuk ADMIN --}}
        @if($role === 'admin')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav-barang" data-bs-toggle="collapse" href="#">
                <i class="bi bi-box"></i><span>Barang</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-barang" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="{{ url('barang') }}">
                        <i class="bi bi-box" style="font-size:12pt;"></i>
                        <span>List Barang</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-truck"></i><span>Driver</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="{{ url('driver') }}">
                        <i class="bi bi-plus" style="font-size:12pt;"></i>
                        <span>Driver List</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/orders/driver') }}">
                        <i class="bi bi-truck" style="font-size:12pt;"></i>
                        <span>List Order Driver</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- customer -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav-customer" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Customer</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-customer" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="{{ url('/customer') }}">
                        <i class="bi bi-table" style="font-size:12pt;"></i>
                        <span>Customer List</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/orders/driver') }}">
                        <i class="bi bi-truck" style="font-size:12pt;"></i>
                        <span>List Order Customer</span>
                    </a>
                </li>
              </ul>
        </li>

        @endif

        @if($role === 'customer')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav-customer" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Customer</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav-customer" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="{{ url('/customer') }}">
                        <i class="bi bi-table" style="font-size:12pt;"></i>
                        <span>Customer List</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/orders') }}">
                        <i class="bi bi-truck" style="font-size:12pt;"></i>
                        <span>List Order Customer</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @if($role === 'driver')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-truck"></i><span>Driver</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="{{ url('/driver') }}">
                        <i class="bi bi-plus" style="font-size:12pt;"></i>
                        <span>Driver List</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/orders/driver') }}">
                        <i class="bi bi-truck" style="font-size:12pt;"></i>
                        <span>List Order Driver</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

    </ul>
</aside>
