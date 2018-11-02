@extends('layouts.layout')
    
    @section('content')
    <section class="route__body informe-box">
        <h1>Informe de reportes de calidad</h1>
        <div class="msg-displayer" id="msg-displayer">
            
        </div>
        <form name="gen-informe" class="" action="" method="get">
            <p>
            <label for="dateini"> Fecha de inicio </label><br />
            <input type="date" name="dateini" value="" class="form__dateini form_informe_input" id="dateini" placeholder="YYYY-MM-DD" required pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" title="Ingrese fecha en el formato YYYY-MM-DD"/>
            </p>
            <p>
            <label for="datefin"> Fecha de cierre </label><br />
            <input type="date" name="datefin" value="" class="form__datefin form_informe_input" id="datefin" placeholder="YYYY-MM-DD" required pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" title="Ingrese fecha en el formato YYYY-MM-DD" />
            </p>
            <p>
            <button type="" id="btn-generar-informe" class="form_informe_input">Generar Informe</button>
            </p>
        </form>
     </section>
    

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn-generar-informe").on("click",function(e){
            e.preventDefault();
            let dateini = $("input#dateini").val();
            let datefin = $("input#datefin").val(); 
            fnChangeEvtBoxStyle('processing');
            $("#msg-displayer").html("Generando el informe...");


            $.ajax({
                url: '/informe',
                method:'post',
                data: {
                    dateini: dateini,
                    datefin: datefin,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data){
                    if (data.data.success){
                        $("#msg-displayer").html(data.data.msg);
                        fnChangeEvtBoxStyle('ok');
                    }
                    else {
                        $("#msg-displayer").html(data.data.msg);
                        fnChangeEvtBoxStyle('error');   
                    }

                },
                error: function(data){
                    $("#msg-displayer").html(data.data.msg);
                    fnChangeEvtBoxStyle('error'); 
                }
            });

        });



        let fnChangeEvtBoxStyle = function(typeStyle){




            if (typeStyle == 'ok'){
                $("#msg-displayer").removeClass("msg-displayer--error").removeClass("msg-displayer--processing").addClass("msg-displayer--ok");      
            }
            else if (typeStyle == 'error'){
                $("#msg-displayer").removeClass("msg-displayer--ok").removeClass("msg-displayer--processing").addClass("msg-displayer--error");                
            }
            else if ((typeStyle == 'processing')){
                $("#msg-displayer").removeClass("msg-displayer--ok").removeClass("msg-displayer--error").addClass("msg-displayer--processing"); 
                return 1;
            }




            setTimeout(function(){
                $("#msg-displayer").removeClass("msg-displayer--ok");
                $("#msg-displayer").removeClass("msg-displayer--error");
                $("#msg-displayer").removeClass("msg-displayer--processing");
                $("#msg-displayer").html("");
            },8000);

        }

    </script>
    @endsection
