$(document).ready(function() {	
    token = $('meta[name="csrf-token"]').attr('content');
    $("#errorEvaluacion").hide();
    $("#guardaEvaluacion").hide();
    $("#anterior").hide();
    $("#siguiente").show();
    var noTabs = $("#noTabs").val();                                                                                             
    var tabActual = 1;
    var secciones = new Array(noTabs);
    secciones[0] = noTabs;
    
    for(var i=1; i<=noTabs; i++){
        if(i> 1){
            $("#tab"+i).hide();
        }
        secciones[i] = $('.seccionPregunta-'+i).length;
    }
    
    
    $("#siguiente").click(function(){     
        var faltantes = validaRespuestas(secciones[tabActual], 'radios-'+tabActual);

        if( faltantes === 0){            
            $("#tab"+tabActual).hide();        
             tabActual++;
             if(tabActual >= noTabs){
                 $("#siguiente").hide();
                 $("#anterior").show();
                 $("#guardaEvaluacion").show();
             }else{
                 $("#anterior").show();
             }
             $('.nav-tabs > .active').next('li').find('a').trigger('click');
             $("#tab"+tabActual).show();     
        }else{
            console.log("error");
            error("Por favor revisa tus respuestas, existen " + faltantes + " por responder." );
        }
    });


    //Boton para guardar cuestionario
    $("#guardaEvaluacion").click(function(){
        var valores = '';
        $(".opciones").each(function( index ) {
            if($(this).is(':checked')) {  
                valores += $(this).attr('id')+'|';
            }
        })
        $("#valores").val(valores);
        return true;
    })

    //functiones auxiliares    
    var validaRespuestas = function(total, idValor) {
        var radiosSeleccionados = 0;
        var radiosPintados = total;
        console.log(total, idValor);
        $("."+idValor).each(function( index ) {
            if($(this).is(':checked')) {  
                radiosSeleccionados ++;
            }
          }); 
          if(radiosSeleccionados === radiosPintados){
              return 0;
          }
        return (radiosPintados - radiosSeleccionados);
    };


    $("#anterior").click(function(){
        $("#guardaEvaluacion").hide();
        $("#tab"+tabActual).hide();
        tabActual--;
        if(tabActual <=1){
            $("#siguiente").show();
            $("#anterior").hide();            
        }else{
            $("#siguiente").show();
        }
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
        $("#tab"+tabActual).show();
    });

    jQuery.validaRespuestas = function(total) { 
        console.log("en la funcion:  " + total);ss
      };
    /*$('.nav-tabs a').on('shown.bs.tab', function(event){
        var x = $(event.target).text();         // active tab
        var y = $(event.relatedTarget).text();  // previous tab
        console.log(x + " -------- " + y);
      });
*/
});