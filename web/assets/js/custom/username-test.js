$(document).ready(function(){
   $('.username').blur(function(){
      var username = this.value;//valor del input
      
      $.ajax({
          url:URL+'/username-test',
          data: {username: username},
          type: 'POST',
          success: function(response){
                if (response == "used") {
                  $('.username').css('border-color','red');
                }else{
                  $('.username').css('border-color','green');
                }
          }
      });
      
   });
    
    
});