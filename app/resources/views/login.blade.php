<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <link rel="apple-touch-icon" href="icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="{{ $app['url']->to('/') }}/css/normalize.css">
        <link rel="stylesheet" href="{{ $app['url']->to('/') }}/css/main.css">
    </head>
    <body>
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <div id="app">
        	
        	<header>
        		<figure>
					<img src="imgs/SIBA_Logo_120x65.png" style="width: 5rem;" />
				</figure>
        	</header>
			<section class="route__body"">
				<div class="box-form" style="position: relative; padding: 4rem; 1rem; 2rem; 1rem;">
					<form>
						<div class="form-box__input-box">
							<div class="form-box__input-box__title">
								Usuario
							</div>
							<div class="form-box__input-box__input">
								<input type="text" name="reporte-error-nocoincide-guia" id="reporte-error-nocoincide-guia" placeholder="Ingrese el evento que hay en la guía...">
							</div>
						</div>
						<div class="form-box__input-box">
							<div class="form-box__input-box__title">
								Clave
							</div>
							<div class="form-box__input-box__input">
								<input type="password" name="reporte-error-nocoincide-guia" id="reporte-error-nocoincide-guia" placeholder="Ingrese el evento que hay en la guía...">
							</div>
						</div>
						<div class="form-box__input-box__buttons">
							<button>Ingresar</button>
						</div>
					</form>
				</div>
			</section>
        </div>
    </body>
</html>