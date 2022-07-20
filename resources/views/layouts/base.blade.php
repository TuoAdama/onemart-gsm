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
	<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	@stack('css')

</head>

<body class="app">
	@include('partials.sidebar')
	<!--//app-header-->

	<div class="app-wrapper">
		@yield('content')
	</div>
	<!--//app-wrapper-->


	<!-- Javascript -->
	<script src="{{asset('assets/js/jquery.js')}}"></script>
	<script src="{{asset('assets/plugins/popper.min.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		  $('#table').DataTable();
	  } );
	</script>

	@stack('scripts')

</body>

</html>