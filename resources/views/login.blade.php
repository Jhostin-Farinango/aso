<!DOCTYPE html>
<html>
	
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
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
			background: #efb616;
		}
		#contenedor{
			width: 100%;
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#login{
			background: #fff;
			padding: 15px;
			border-radius: 15px;
			display: flex;
			flex-direction: column;
			align-items: center;
		}
		input:focus{
			border: 1px solid #0d6efd !important;
		}
	</style>
</head>
<body>
	<div id="contenedor">
		<div id="login">
			<h1>ASOCONAPPE</h1>
			<h2>Login</h2>
			<input type="text" id="alias" placeholder="Usuario">
			<input type="password" id="clave" class="mt-3" placeholder="Contraseña">
			<button class="btn btn-md btn-primary mt-3" id="entrar" onclick="entrar()">Iniciar Sesión</button>
		</div>
	</div>
	<script type="text/javascript">
		let server='<?= env("APP_URL") ?>';
		function entrar(){
			let alias=document.getElementById("alias").value;
			let clave=document.getElementById("clave").value;
			axios.post('/api/login', {
				alias: alias,
				clave: clave
			})
		    .then(function (response) {
		        let respuesta=response.data;
		        if(respuesta.respuesta){
			        /*if(respuesta.rol=="Admin"){
			        	window.location.href=server+"/dashboardadmin";
			        }
			        else if(respuesta.rol=="Socio"){
			        	//window.location.href=server+"/dashboard";
			        	window.location.href=server+"/sesion/"+respuesta.id_usuario+"/"+respuesta.rol;
			        }*/
			        window.location.href=server+"/sesion/"+respuesta.id_usuario+"/"+respuesta.rol;
		        }
		        else{
		        	alert("Error: credenciales incorrectas");
		        }
		    })
		    .catch(function (error) {
		        console.log(error);
		    });
		}
	</script>
</body>
</html>