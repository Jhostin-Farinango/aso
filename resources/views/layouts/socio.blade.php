<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SIAS</title>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<script src="{{ asset('js/app.js') }}" defer></script>
	<style>
		*, html{
			padding: 0;
			margin: 0;
			box-sizing: border-box;
		}
		body{
			background: #c5c5c5;
		}
		#menu{
			background: #efb616 !important;
		}
		.pointer{
			cursor: pointer;
		}
	</style>
	<script src="https://kit.fontawesome.com/a948d029c9.js" crossorigin="anonymous"></script>
</head>
<body>
	<nav id="menu" class="navbar navbar-expand-lg bg-light">
	 	<div class="container-fluid">
	    	<a class="navbar-brand" href="#">SIAS</a>
	    	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	      		<span class="navbar-toggler-icon"></span>
	    	</button>
	    	<div class="collapse navbar-collapse" id="navbarNav">
	      		<ul class="navbar-nav">
	        		<li class="nav-item">
	          			<a class="nav-link active" aria-current="page" href="<?= env("APP_URL") ?>/dashboard"><i class="fas fa-home"></i> Inicio</a>
	        		</li>
	        		<li class="nav-item">
	          			<a class="nav-link active" href="<?= env("APP_URL") ?>/aportes"><i class="far fa-money-bill-alt"></i> Aportes</a>
	        		</li>
	      		</ul>
	      		<ul class="navbar-nav ms-auto">
	      			<li class="nav-item">
	          			<a class="nav-link active" href="<?= env("APP_URL") ?>/cerrar"><i class="fas fa-door-open"></i> Cerrar Sesi&oacute;n</a>
	        		</li>
	      		</ul>
		    </div>
	  	</div>
	</nav>

	<main class="py-4">
        @yield('content')
    </main>
	
</body>
</html>