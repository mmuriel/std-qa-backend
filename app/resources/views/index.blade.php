@extends('layouts.layout')
	

	@section('content')
				<div class="app__buscador">
	        		<input type="text" name="q" id="q" class="buscador" placeholder="Buscar..." />
	        	</div>
				<div class="horas">
					<div class="horas__hora" id="hora_1">{{ date("H:i",$timebase) }}</div>
					<div class="horas__hora" id="hora_1">{{ date("H:i",($timebase + 900)) }}</div>
				</div>
				<section class="route__body">
					<div style="" class="slick-arrow" id="slick-arrow-left"><a href="?tb={{ $linksArrow['back'] }}"><span class="icon-nav-left"></span></a></span></div>
					<ul class="canales listview_vertical">
						@for ($i = 0; $i < count($programacion); $i++)
						<li class="canal" data-chn="{{ $programacion[$i]['chn']['id'] }}">
							<div class="canal__header">
								{{ $programacion[$i]['chn']['name'] }}
							</div>
							<ul class="canal__programas listview_horizontal" data-chn="{{ $programacion[$i]['chn']['id'] }}">
								@for ($j = 0 ; $j < count($programacion[$i]['evts']); $j++)
								
											{!! $programacion[$i]['evts'][$j]->render() !!}
								
								@endfor
							</ul>
						</li>
						@endfor
					</ul>
					<div style="" class="slick-arrow" id="slick-arrow-right"><a href="?tb={{ $linksArrow['next'] }}"><span class="icon-nav-right"></span></a></div>
				</section>
				<script>

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					})
					
					/* Despacha un reporte OK de un evento */
					$(".canal__programas__programa__btns__btnok").on("click",function(e){

						e.preventDefault();
						let idprg = $(e.target).attr("data-idprg");
						let idchn = $(e.target).parents(".canal").attr('data-chn');

						console.log(idchn);
						$.ajax({
							url: '/reporte',
							method:'post',
							data: {
								evt: idprg,
								tipo: 1,
								chn: idchn,
								_token: '{{ csrf_token() }}'

							},
							success: function(data){
								console.log("Proceso exitoso...");
								console.log(data);
								fnChangeEvtBoxStyle('box-'+idprg,'ok');
							},
							error: function(data){

								console.log("Algo falló: ");
								console.log(data);
								//$(".app_buscador").html(data.responseText);
							}
						});

					})

					let fnChangeEvtBoxStyle = function(idbox,typeStyle){

						$("#"+idbox).children(".canal__programas__programa__btns").hide();

						if (typeStyle == 'ok'){

							$("#"+idbox).removeClass("canal__programas__programa").addClass("canal__programas__programa--reportedok");		
							$("#"+idbox).children(".canal__programas__programa__details").css("height","100%");					

						}
						else{
							$("#"+idbox).removeClass("canal__programas__programa").addClass("canal__programas__programa--reportederror");
							$("#"+idbox).children(".canal__programas__programa__details").css("height","100%");					
						}

					}

				</script>
		
	@endsection