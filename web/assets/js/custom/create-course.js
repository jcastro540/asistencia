$(document).ready(function () {

    var fechaInicial = $('#backendbundle_course_startdate');
    var fechaFinal = $('#backendbundle_course_enddate');

    fechaFinal.change(function () {

        if (fechaInicial.val() > fechaFinal.val()) {
            $.alert({
                title: 'Error!',
                theme: 'supervan',
                content: 'La fecha de inicio no puede ser mayor que la fecha en que culmina curso',
            });
            
            $(this).val('');
        }
    });





    var forms = [
        '[ name="{{ postform.vars.full_name }}"]'
    ];

    $(forms.join(',')).on('submit', function (e) {
        if (!valid) {
            e.preventDefault();
            e.unbind();
        }

        postForm($(this), function (response) {
        });

        return false;
    });




    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_flat-green',
        increaseArea: '10%' // optional
    });
});


function postForm($form, callback) {

    /*
     * Obtengo todos los valores del formulario
     */
    var values = {};
    $.each($form.serializeArray(), function (i, field) {
        values[field.name] = field.value;
    });

    /*
     * Envio los valores del form al sevidor
     */
    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: values,
        success: function (data) {
            callback(data);
            console.log(data);
        }
    });

}