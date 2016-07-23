$(document).ready(function() {
    var offset = 2;
    $('#finished-tickets-more-button').unbind('click').click(function(e) {  
    e.preventDefault();
     
        $('<div class="appendMore" style="display:none" />').insertBefore($(this))
            .load('./php/views/helpers/finishedTicketsMoreLoader.php', {offset: offset}, function() {
                $(this).slideDown();
                offset += 2;
            });
        
   });
});