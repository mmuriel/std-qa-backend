<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
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
				<nav>
					<ul>
						<li>
							<a href="/">Home</a>
						</li>
						<li>
							<a href="/logout">Logout</a>
						</li>
					</ul>
				</nav>
        	</header>
			<div class="app__buscador">
        		<input type="text" name="q" id="q" class="buscador" placeholder="Buscar..." />
        	</div>
			<div class="horas">
				<div class="horas__hora" id="hora_1">10:15</div>
				<div class="horas__hora" id="hora_1">10:15</div>
			</div>
			<section class="route__body"">
				<div style="" class="slick-arrow" id="slick-arrow-left"><span class="icon-nav-left"></span></div>
				<ul class="canales listview_vertical">
					<li class="canal">
						<div class="canal__header">
							Canal 1
						</div>
						<ul class="canal__programas listview_horizontal">
							<li class="canal__programas__programa" style="width: 60vw; left: -50vw;">
								<div class="canal__programas__programa__details" style="float:left; width: 10vw; left:50vw;">
									
										20:00 - 
									
									
										Deportivo Cali vs Atletico Nacional, Liga Aguila 2017, Semifinal, el deportivo cali se enfrenta en su estadio por el paso a la final de la liga colombiana.
									
								</div>
								<div class="canal__programas__programa__btns" style="float:left; width: 10vw; left:50vw;">
									<button>Ok</button>
									<button>Error</button>
								</div>
								<div class="clearFloat"></div>
							</li>
							<li class="canal__programas__programa canal__programas__programa--reportedok" style="width: 300vw; left: 10vw;">
								<div class="canal__programas__programa__details">
									
										<a href="http://localhost:8000/evento_ok.html">Deportivo Cali vs Atletico Nacional, Liga Aguila 2017, Semifinal</a>
								</div>
								<div class="clearFloat"></div>
							</li>
						</ul>
					</li>
					<li class="canal">
						<div class="canal__header">
							Canal 2
						</div>
						<ul class="canal__programas listview_horizontal">
							<li class="canal__programas__programa canal__programas__programa--reportederror" style="width: 50vw; left: 0vw;">
								<div class="canal__programas__programa__details ">

										<a href="http://localhost:8000/evento_ok.html">20:00 - Deportivo Cali vs Atletico Nacional, Liga Aguila 2017, Semifinal</a>

								</div>
								<div class="clearFloat"></div>
							</li>
							<li class="canal__programas__programa" style="width: 30vw; left: 50vw;">
								<div class="canal__programas__programa__details">
									<span class="canal__programas__programa__details__time">
										20:00
									</span>
									<span class="canal__programas__programa__details__title">
										Deportivo Cali vs Atletico Nacional, Liga Aguila 2017, Semifinal
									</span>
								</div>
								<div class="canal__programas__programa__btns">
									<button>Ok</button>
									<button>Error</button>
								</div>
								<div class="clearFloat"></div>
							</li>
							<li class="canal__programas__programa" style="width: 100vw; left: 80vw;">
							<div class="canal__programas__programa__details">
									<span class="canal__programas__programa__details__time">
										20:00
									</span>
									<span class="canal__programas__programa__details__title">
										Deportivo Cali vs Atletico Nacional, Liga Aguila 2017, Semifinal
									</span>
								</div>
								<div class="canal__programas__programa__btns">
									<button>Ok</button>
									<button>Error</button>
								</div>
							</li>
						</ul>
					</li>
					<li class="canal">
						<div class="canal__header">
							Canal 3
						</div>
						<ul class="canal__programas listview_horizontal">
							<li class="canal__programas__programa" style="width: 50vw; left: -10vw;">
								<div class="canal__programas__programa__details" style="float:left; width: 40vw; left:10vw;">
									<span class="canal__programas__programa__details__title">
										Nombre del evento 1			
									</span>
									<span>
										(20:00:00)
									</span>
								</div>
								<div class="canal__programas__programa__btns" style="float:left; width: 40vw; left:10vw;">
									<button>Ok</button>
									<button>Error</button>
								</div>
								<div class="clearFloat"></div>
							</li>
							<li class="canal__programas__programa" style="width: 25vw; left: 40vw;">
								<div class="canal__programas__programa__details">
									<span class="canal__programas__programa__details__title">
										Nombre del evento 2			
									</span>
									<span>
										(23:00:00)
									</span>
								</div>
								<div class="canal__programas__programa__btns">
									<button>Ok</button>
									<button>Error</button>
								</div>
							</li>
							<li class="canal__programas__programa" style="width: 100vw; left: 65vw;">
							<div class="canal__programas__programa__details">
									<span class="canal__programas__programa__details__title">
										Nombre del evento 3		
									</span>
									<span>
										(23:30:00)
									</span>
								</div>
								<div class="canal__programas__programa__btns">
									<button>Ok</button>
									<button>Error</button>
								</div>
							</li>
						</ul>
					</li>
				</ul>
				<div style="" class="slick-arrow" id="slick-arrow-right"><span class="icon-nav-right"></span></div>
			</section>
        </div>
    </body>
</html>