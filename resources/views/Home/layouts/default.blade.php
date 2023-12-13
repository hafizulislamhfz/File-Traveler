<!DOCTYPE html>
<html lang="en">

<head>
    @include('Home.includes.head')
</head>

<body>
    @include('Home.includes.navbar')

    @include('Home.pages.home')

    @include('Home.includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('Home/main.js') }}"></script>
</body>

</html>
