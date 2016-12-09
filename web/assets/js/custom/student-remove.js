$('.btn-delete').unbind('click').click(function () {
    $(this).attr('id', 'eliminar');
    $.confirm({
        title: 'Confirmar!',
        content: '¿Seguro de eliminar el estudiante?',
        theme: 'supervan',
        buttons: {
            Confirmar: function () {
                $('#eliminar').parent().parent().parent().parent().parent().fadeOut();
                $.ajax({
                    url: URL + '/delete/student/' + $('#eliminar').attr('data-id'),
                    type: 'GET',
                    success: function (response) {
                        console.log(response);
                    }
                });
                $('.btn-delete').removeAttr('id');
            },
            Cancelar: function () {
                $('.btn-delete').removeAttr('id');
                $.alert({
                    theme: 'supervan',
                    title: 'Cancelado!',
                    content: 'No se ha eliminado el estudiante'
                });
            }
        }
    });
});

