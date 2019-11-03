$(document).ready(function() {  
        
        $('#fechainiacceso').datetimepicker({
        format: 'DD-MM-YYYY',
        inline: false,
        sideBySide: false,
        locale: 'es',
         useCurrent: false //Important! See issue #1075
        });

        $('#fechafinacceso').datetimepicker({
            format: 'DD-MM-YYYY',
            inline: false,
            sideBySide: false,
            locale: 'es',
            useCurrent: false //Important! See issue #1075
        });

         $("#fechainiacceso").on("dp.change", function (e) {
            var date = !e.date ? null : e.date.toDate();
            $("#fecha").val('');

            if(date !== null)
            $("#fecha").attr("disabled",true);
            else
            $("#fecha").attr("disabled",false);    

            $('#fechafinacceso').data("DateTimePicker").minDate(e.date);
        });
        $("#fechafinacceso").on("dp.change", function (e) {
             var date = !e.date ? null : e.date.toDate();
             $("#fecha").val('');
            if(date !== null)
            $("#fecha").attr("disabled",true);
            else
            $("#fecha").attr("disabled",false);

            $('#fechainiacceso').data("DateTimePicker").maxDate(e.date);
        });

        

});