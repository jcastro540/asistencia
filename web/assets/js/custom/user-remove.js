$('.btn-delete').unbind('click').click(function () {
    
$(this).parent().parent().parent().fadeOut();
    $.ajax({
        url: URL+'/user/remove/'+$(this).attr('data-id'),
        type: 'GET',
        success: function (response) {
            console.log(response);
        }
    });
});