$(document).ready(function() {
    
   $('#login-submit').click(function() {
      $.ajax({
          url: "php/init/loginHandler.php",
          
      });
   });
   
});