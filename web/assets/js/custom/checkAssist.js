$(document).ready(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        checkboxClass: 'icheckbox',
        increaseArea: '10%' // optional 
    });


        $('.btn-asist').unbind('click').click(function () {
            $(this).addClass("hidden");
            $(this).parent().find('.btn-unasist').removeClass('hidden');
            $.ajax({
                url: URL +'/assist',
                type: 'POST',
                data: {
                    course: $(this).attr("data-course"),
                    student: $(this).attr("data-student"),
                    class: $(this).attr("data-class"),
                    asistio: "true"
                },
                success: function (response) {
                    console.log(response);
                }
            });
        });
        
        $('.btn-unasist').unbind('click').click(function () {
            $(this).addClass("hidden");
            $(this).parent().find('.btn-asist').removeClass('hidden');
            $.ajax({
                url: URL +'/unassist',
                type: 'POST',
                data: {
                    course: $(this).attr("data-course"),
                    student: $(this).attr("data-student"),
                    class: $(this).attr("data-class")
                },
                success: function (response) {
                    console.log(response);
                }
            });
        });
        
});

