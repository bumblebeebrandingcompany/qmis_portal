<!DOCTYPE html>
<html lang="{{session('locale')}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name', 'LMS')}}</title>

    {{-- <link href="{{ asset('css/client.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('css/adminltev3.css') }}" rel="stylesheet" />

    @yield('styles')

    <!-- Microsoft Clarity Tracking Code -->
    <script type="text/javascript">
      (function(c,l,a,r,i,t,y){
          c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
          t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
          y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
      })(window, document, "clarity", "script", "o1w0kvwycw");
  </script>

</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    @yield('content')
    @yield('scripts')
  
</body>

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6N1ZD14VM3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-6N1ZD14VM3');
</script>

</html>
