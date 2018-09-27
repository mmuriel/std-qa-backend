@extends('layouts.layout')
	

	@section('content')
			<section class="route__body"">
				
				{!! $evtView->render() !!}

				<a href="/?{{ base64_decode($qback) }}">Atrás</a>
				<h3>Reportes</h3>
				<div class="reportes">
					<table class="reportes__list">
						<tr>
							<th>Fecha</th>
							<th>Tipo Reporte</th>
							<th>Tipo de Error</th>
						</tr>
						@for ($i = 0; $i < count($reportes); $i++)
							@if ($reportes[$i]->tipo == 1)
						<tr class="reportes__list__reporte--ok">
							@elseif ($reportes[$i]->tipo == 0)
						<tr class="reportes__list__reporte--error">
							@endif
							<td>
								{{ $reportes[$i]->created_at }}
							</td>
							<td>
								@if ($reportes[$i]->tipo == 1)
									Ok
								@elseif ($reportes[$i]->tipo == 0)
									Error
								@endif
							</td>
							<td>
								
								@if ($reportes[$i]->tipo == 0 && $reportes[$i]->errores->first() != NULL )
									@if ($reportes[$i]->errores->first()->tipo == 1 )
										Desfase horario
									@elseif ($reportes[$i]->errores->first()->tipo == 2 )
										Programación no coincide
									@elseif ($reportes[$i]->errores->first()->tipo == 3 )
										Otros
									@endif
								@endif
							</td>
						</tr>
						@endfor
					</table>
				</div>

				<div class="form-box">
					<h3>Reporte</h3>
					<div class="form-box__input-box">
						<div class="form-box__input-box__title">
							Estado del evento:
						</div>
						<div class="form-box__input-box__input">
							<div class="form-box__input-box__input__typeselector">
							<label for="reporte-tipo-ok">Correcto</label>
							<input type="radio" name="reporte-tipo" value="ok" id="reporte-tipo-ok">
							</div>
							<div class="form-box__input-box__input__typeselector">
							<label for="reporte-tipo-error">Errado</label>
							<input type="radio" name="reporte-tipo" value="error" id="reporte-tipo-error">
							</div>
						</div>
					</div>
					<div class="form-box__input-box form-box__input-box--closed" id="error_tipo_box">
						<div class="form-box__input-box__title">
							Tipo de error:
						</div>
						<div class="form-box__input-box__input">
							<select name="error_tipo" id="error_tipo">
								<option value=""></option>
								<option value="1">Desfase horario</option>
								<option value="2">Programación no coincide</option>
							</select>
						</div>
					</div>
					<div class="form-box__input-box form-box__input-box--closed" id="error_motivo_box">
						<div class="form-box__input-box__title">
							Motivo del error:
						</div>
						<div class="form-box__input-box__input">
							<select name="error_tipo" id="error_motivo">
								<option value=""></option>
								<option value="1">Origen</option>
								<option value="2">Error humano</option>
								<option value="3">Evento en vivo</option>
							</select>
						</div>
					</div>
					<div class="form-box__input-box form-box__input-box--closed" id="error_transmitiendo_box">
						<div class="form-box__input-box__title">
							Programación no coincide (detalles):
						</div>
						<div class="form-box__input-box__input">
							
							<input type="text" name="error_transmitiendo" id="error_transmitiendo" placeholder="Transmitiendo..." style="padding: 0.5rem;">
							
						</div>
					</div>
					<div class="form-box__input-box form-box__input-box--closed" id="error_desfase_box">
						<div class="form-box__input-box__title">
							Tiempo desfase:
						</div>
						<div class="form-box__input-box__input">
							<input type="number" name="error_desfase" value="" id="error_desfase" >
						</div>
					</div>
					<div class="form-box__input-box form-box__input-box--closed" id="error_detalle_box">
						<div class="form-box__input-box__title">
							Detalles:
						</div>
						<div class="form-box__input-box__input">
							<textarea name="error_detalle" id="error_detalle"></textarea>
						</div>
					</div>
					
					<div class="form-box__btns">
						<button disabled="disabled" class="form-box__btns__btn form-box__btns__btn--disabled" id="form-box__btns__btn__send">Generar Reporte</button>
					</div>
				</div>
			</section>


<script>
$(document).ready(function(){

	$("input[name='reporte-tipo']").on("change",function(e){

		e.preventDefault();
		console.log($(e.target).val());
		let repoTipo = $(e.target).val();
		disableAllInputBoxes();
		if (repoTipo=='ok'){
			enableBtn('#form-box__btns__btn__send');
		}
		else if(repoTipo=='error'){
			enableInputBox("#error_tipo_box");
			disableBtn('#form-box__btns__btn__send','');
		}
		else{
			disableBtn('#form-box__btns__btn__send','');
		}

	});


	$("select#error_tipo").on("change",function(e){

		e.preventDefault();
		let error_tipo = $(e.target).val();
		console.log(error_tipo);
		if (error_tipo != ''){
			enableInputBox('#error_motivo_box');
			if (error_tipo=='2'){//Programacion no coincide

				enableInputBox('#error_transmitiendo_box');
				enableInputBox('#error_detalle_box');

				disableInputBox('#error_desfase_box');
				resetValueInput("#error_desfase");
				resetValueInput("#error_detalle");

			}
			else if (error_tipo=='1'){//Desfase horario

				enableInputBox('#error_desfase_box');
				enableInputBox('#error_detalle_box');

				disableInputBox('#error_transmitiendo_box');
				resetValueInput("#error_transmitiendo");
				resetValueInput("#error_detalle");
			}
			else{
			}
		}
		else{
				disableInputBox('#error_motivo_box');
				resetValueInput("#error_motivo");
				disableBtn('#form-box__btns__btn__send','');
			
		}
	});


	$("select#error_motivo").on("change",function(e){

		e.preventDefault();
		let error_motivo = $(e.target).val();
		console.log(error_motivo);
		if (error_motivo != ''){
			enableInputBox('#error_motivo_box');
			enableBtn('#form-box__btns__btn__send');
		}
		else{
			disableInputBox('#error_motivo_box');
			resetValueInput("#error_motivo");
		}
	});


	$("#form-box__btns__btn__send").on("click",function(e){

		e.preventDefault();
		//console.log("Boton de enviar formulario...");
		disableBtn("#form-box__btns__btn__send","Procesando...");

		let reporte_tipo = $("input[name='reporte-tipo']:checked").val();
		let data = {}

		if (reporte_tipo == 'ok'){

			let valid = validateOkForm();
			if (valid.validation == true){
				data = {

					evt: '{{ $evento->id }}',
					tipo: '1',
					chn: '{{ $evento->channel()->id }}',
					evt_titulo: '{{ $evento->title }}',
					evt_fechahora: '{{ $evento->begin }}',
					_toke: '{{ csrf_token() }}'

				}
				
				sendReport(data,function(response){
					//Callback para Ok
					console.log(response);
					console.log("Registro correcto para Reporte OK...");
					$(".reportes__list > tbody").html($(".reportes__list > tbody").html()+" "+"<tr class='reportes__list__reporte--ok'><td>"+response.reporte.created_at+"</td><td>Ok</td><td></td></tr>");
					disableAllInputBoxes();
					disableBtn("#form-box__btns__btn__send","Generar Reporte");


				},function(response){
					//Call back para error
					console.log("Registro fallido para Reporte OK...");
					disableAllInputBoxes();
					alert("Fallo en el registro del reporte...");
					disableBtn("#form-box__btns__btn__send","Generar Reporte");
				});
				
			}
			else{
				alert (valid.msg);
			}
			

		}
		else if (reporte_tipo == 'error'){

			let valid = validateErrorForm();
			if (valid.validation == true){
				let error_tipo = $("#error_tipo").val();
				let error_motivo = $("#error_motivo").val();
				let error_detalle = $("#error_detalle").val();
				if (error_detalle==''){
					error_detalle = '-';
				}
				if (error_tipo == '1'){
					data = {
						evt: '{{ $evento->id }}',
						tipo: '0',
						chn: '{{ $evento->channel()->id }}',
						evt_titulo: '{{ $evento->title }}',
						evt_fechahora: '{{ $evento->begin }}',
						_toke: '{{ csrf_token() }}',
						error_tipo: error_tipo,
						error_motivo: error_motivo,
						error_detalle: error_detalle,
						error_desfase: $("#error_desfase").val(),
						error_transmitiendo: '-'
					}
				}
				else if (error_tipo == '2'){

					data = {
						evt: '{{ $evento->id }}',
						tipo: '0',
						chn: '{{ $evento->channel()->id }}',
						evt_titulo: '{{ $evento->title }}',
						evt_fechahora: '{{ $evento->begin }}',
						_toke: '{{ csrf_token() }}',
						error_tipo: error_tipo,
						error_motivo: error_motivo,
						error_detalle: error_detalle,
						error_desfase: '0',
						error_transmitiendo: $("#error_transmitiendo").val(),
					}
				}
				sendReport(data,function(response){
					//Callback para Ok
					console.log("Registro correcto para Reporte Error...");
					let motivoErrorDis = '';
					if (response.error.motivo == '1'){
						motivoErrorDis = 'Desfase horario';
					}
					else if (response.error.motivo == '2'){
						motivoErrorDis = 'Programación no coincide';
					}
					$(".reportes__list > tbody").html($(".reportes__list > tbody").html()+" "+"<tr class='reportes__list__reporte--error'><td>"+response.reporte.created_at+"</td><td>Error</td><td>"+motivoErrorDis+"</td></tr>");
					disableAllInputBoxes();
					disableBtn("#form-box__btns__btn__send","Generar Reporte");
				},function(response){
					//Call back para error
					console.log("Registro fallido para Reporte Error...");
					alert ("Registro fallido para Reporte Error...");
					disableBtn("#form-box__btns__btn__send","Generar Reporte");
				});			
			}
			else{
				alert (valid.msg);
			}
		}
	})
})

let enableBtn = function (btnSelector){

	$(btnSelector).prop("disabled", false);
	$(btnSelector).removeClass("form-box__btns__btn--disabled");

}

let disableBtn = function (btnSelector,legend){

	$(btnSelector).prop("disabled", true);	
	$(btnSelector).addClass("form-box__btns__btn--disabled");
	if (legend!='')
		$(btnSelector).html(legend);

}






let enableInputBox = function (boxSelector){

	$(boxSelector).removeClass("form-box__input-box--closed");

}

let disableInputBox = function (boxSelector){

	$(boxSelector).addClass("form-box__input-box--closed");

}

let disableAllInputBoxes = function (){

	/* Disables Tipo */
	disableInputBox('#error_tipo_box');
	/* Disables Motivo */
	disableInputBox('#error_motivo_box');
	/* Disables Motivo */
	disableInputBox('#error_transmitiendo_box');
	/* Disables Detalle */
	disableInputBox('#error_detalle_box');
	/* Disables Desfase */
	disableInputBox('#error_desfase_box');

}


let resetValueInput = function(inputSelector){

	$(inputSelector).val('');

}


let validateOkForm = function(){

	let validation = {

		validation: true,
		msg: '',
	}
	return (validation);

}



let validateErrorForm = function(){

	let validation = {

		validation: true,
		msg: '',
	};
	let error_tipo = $("#error_tipo").val();
	let error_motivo = $("#error_motivo").val();
	//1. Valida que es el tipo no sea vacio
	if (error_tipo == ''){

		validation.validation = false;
		validation.msg += "No se ha definido el tipo de error\n" ;
		$("#error_tipo_box").addClass('inputFieldError');

	}

	if (error_motivo == ''){

		validation.validation = false;
		validation.msg += "No se ha definido el motivo de error\n";
		$("#error_tipo_box").addClass('inputFieldError');

	}

	if (error_tipo == '1'){
		//Valida campo si tipo es Desfase horario
		let error_desfase = $("#error_desfase").val();
		if (error_desfase == ''){

			validation.validation = false;
			validation.msg += "No se ha definido el tiempo de desfase\n";
			$("#error_desfase_box").addClass('inputFieldError');			

		}

	}
	else if (error_tipo == '2'){
		//Valida campo si tipo es Programación no coincide
		let error_transmitiendo = $("#error_transmitiendo").val();
		if (error_transmitiendo == ''){

			validation.validation = false;
			validation.msg += "No se ha definido el evento que se está transmitiendo\n";
			$("#error_transmitiendo_box").addClass('inputFieldError');			

		}
	}


	return (validation);

}


let sendReport = function(data,callback,errorCallback){

	$.ajax({
		url: '/reporte',
		method:'post',
		data: data,
		success: function(res){
			console.log("Proceso exitoso...");
			console.log(res.data);
			callback(res.data);
		},
		error: function(res){

			console.log("Algo falló: ");
			console.log(res.data);
			errorCallback(res.data);
			//$(".app_buscador").html(data.responseText);
		}
	});

}

</script>





	@endsection