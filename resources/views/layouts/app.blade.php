<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DatVeXe.vn')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-5ZFh0IP9N6byN1Nsx3Rp3XIan+FJxuxMxDPZWS9Vyuk3F7S3w7Dnk3a1JpN96CB2A+FiYqSdz+CA0nVddOZfgw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>

<body class="app-body">
  <div class="app-shell">
    @include('layouts.header')

    @hasSection('heading')
      <section class="page-hero">
        <div class="container d-flex flex-column gap-2">
          <p class="page-hero__eyebrow text-uppercase">Bảng điều khiển</p>
          <h1 class="page-hero__title">@yield('heading')</h1>
        </div>
      </section>
    @endif

    <main class="content-area">
      @yield('content')
    </main>

    @include('layouts.footer')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  @yield('scripts')
</body>
</html>