<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'NiceAdmin Bootstrap Template')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <!-- drop down -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />


</head>

<body>

  @include('layout.header')
  @include('layout.sidebar')

  <main id="main" class="main">
    @yield('content')
  </main>

  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

  <script type="text/javascript">
    let darkMode = false;

    function toggleDarkMode() {
      // Toggle class 'dark-mode' pada body
      document.body.classList.toggle('dark-mode');
      // Perbarui nilai darkMode
      darkMode = !darkMode;
      // Simpan tema yang dipilih di localStorage
      localStorage.setItem('theme', darkMode ? 'dark' : 'light');
      // Perbarui ikon sesuai dengan mode yang dipilih
      updateDarkModeIcon();
    }

    // Fungsi untuk memperbarui ikon sesuai dengan mode yang dipilih
    function updateDarkModeIcon() {
      const darkModeIcon = document.getElementById('dark-mode-icon');
      if (darkMode) {
        darkModeIcon.classList.remove('bi-moon-fill');
        darkModeIcon.classList.add('bi-sun-fill');
      } else {
        darkModeIcon.classList.remove('bi-sun-fill');
        darkModeIcon.classList.add('bi-moon-fill');
      }
    }

    // Periksa tema yang disimpan di localStorage saat halaman dimuat
    if (localStorage.getItem('theme') === 'dark') {
      darkMode = true;
    } else if (localStorage.getItem('theme') === 'light') {
      darkMode = false;
    }

    // Terapkan tema awal saat halaman dimuat
    if (darkMode) {
      document.body.classList.add('dark-mode');
    } else {
      document.body.classList.remove('dark-mode');
    }

    // Event listener untuk tombol dark mode
    document.getElementById('dark-mode-toggle').addEventListener('click', toggleDarkMode);

    // Perbarui ikon saat halaman dimuat
    updateDarkModeIcon();
  </script>
@yield('scripts')


</body>


</html>
