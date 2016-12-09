$(document).ready(function () {
    var img = $('#img');
    var show = $('.preview');

    img.insertAfter(show);
    //img.css('display','none');
    setInterval(function(){ 
        img.fadeIn();
    }, 1000);
    
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#img').css({                
                'margin-bottom': '10px'
            });
            $('#img').fadeIn();
            $('#img')
                    .attr('src', e.target.result)
                    .width('250px')
                    .height('auto');
        };

        reader.readAsDataURL(input.files[0]);
    }
}
