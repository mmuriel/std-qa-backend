@extends('layouts.layout')
	

	@section('content')
				<div class="app__buscador">
					<form action="" method="get" >
					@if ($q == 'all')
	        		<input type="text" name="q" id="q" class="buscador" placeholder="Buscar..." />
	        		@else
					<input type="text" name="q" id="q" class="buscador" placeholder="Buscar..." value="{{ $q }}"/>
	        		@endif
	        		<input type="submit" name="btnbuscar" value="Buscar">
	        		</form>
	        	</div>
				<div class="horas fixme">
					<div class="horas__hora" id="hora_1">{{ date("H:i",$timebase) }}</div>
					<div class="horas__hora" id="hora_1">{{ date("H:i",($timebase + 900)) }}</div>
				</div>
				<section class="route__body">
					<div style="" class="slick-arrow" id="slick-arrow-left"><a href="?{{ $linksArrow['back'] }}"><span class="icon-nav-left"></span></a></span></div>
					<ul class="canales listview_vertical">
						@if (isset($programacion['nodata']))
						<li class="canal">
							<div class="canal__header">
								No data
							</div>
						</li>
						@else
							
							@for ($i = 0; $i < count($programacion); $i++)
							<li class="canal" data-chn="{{ $programacion[$i]['chn']->channel }}">
								<div class="canal__header">
									@if ($programacion[$i]['chn']['frequency'] != null && $programacion[$i]['chn']['frequency'] != 'null')
										({{ $programacion[$i]['chn']['frequency'] }}) {{ $programacion[$i]['chn']['name'] }}
									@else
										{{ $programacion[$i]['chn']['name'] }}
									@endif
								</div>
								<ul class="canal__programas listview_horizontal" data-chn="{{ $programacion[$i]['chn']['id'] }}">
									@for ($j = 0 ; $j < count($programacion[$i]['evts']); $j++)
									
												{!! $programacion[$i]['evts'][$j]->render() !!}
									
									@endfor
								</ul>
							</li>
							@endfor
							
						@endif

					</ul>
					<div style="" class="slick-arrow" id="slick-arrow-right"><a href="?{{ $linksArrow['next'] }}"><span class="icon-nav-right"></span></a></div>
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
						let evtTitle = $(e.target).attr('data-evttitle');
						let evtDatetime = $(e.target).attr('data-evtdatetime');

						console.log(idchn);
						$.ajax({
							url: '/reporte',
							method:'post',
							data: {
								evt: idprg,
								tipo: 1,
								chn: idchn,
								evt_titulo: evtTitle,
								evt_fechahora: evtDatetime,
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




					let fixmeTop = $('.fixme').offset().top;
					$(window).scroll(function(){

					    let currentScroll = $(window).scrollTop();
					    //console.log(fixmeTop+' - '+currentScroll);
					    if (currentScroll >= fixmeTop) {
					        $('.fixme').css({
					            position: 'fixed',
					            top: '0',
					            left: '0',
					            zIndex: '99999'
					        });
					        $('.canales').css({
					        	marginTop:'1.5rem'
					        });
					    } else {
					        $('.fixme').css({
					            position: 'relative'
					        });
					        $('.canales').css({
					        	marginTop:'0rem'
					        });
					    }
					});

				</script>
		
	@endsection