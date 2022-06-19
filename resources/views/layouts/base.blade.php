<!DOCTYPE html>
<html lang="fr">

<head>
	<title>ONEMART- GSM</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Media">
	<link rel="shortcut icon" href="favicon.ico">

	<!-- FontAwesome JS-->
	<script defer src="{{asset('assets/plugins/fontawesome/js/all.min.js')}}"></script>

	<!-- App CSS -->
	<link id="theme-style" rel="stylesheet" href="{{asset('assets/css/portal.css')}}">
	@stack('css')

</head>

<body class="app">
	@include('partials.header')
	<!--//app-header-->

	<div class="app-wrapper">
		@yield('content')
	</div>
	<!--//app-wrapper-->


	<!-- Javascript -->
	<script src="{{asset('assets/plugins/popper.min.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

	<!-- Charts JS -->
	<script src="{{asset('assets/plugins/chart.js/chart.min.js')}}"></script>
	<script src="{{asset('assets/js/index-charts.js')}}"></script>

	<!-- Page Specific JS -->
	<script src="{{asset('assets/js/app.js')}}"></script>

	@stack('scripts')

</body>

</html>