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
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	@stack('css')

</head>

<body class="app">
	@include('partials.sidebar')
	<!--//app-header-->

	<div class="app-wrapper">
		@php
			$restart = App\Http\Controllers\SettingController::restartSysteme();
		@endphp
		@if ($restart)
			<div class="alert alert-danger m-3">Veuillez redemarrer le système</div>
		@endif
		@yield('content')
	</div>
	<!--//app-wrapper-->


	<!-- Javascript -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		  $('#table').DataTable({
			'order':[[0, 'desc']]
		  });
	  } );
	</script>

</body>

</html>