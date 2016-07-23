$(document).ready(function() {
    
    plays = {};
    
    $('.matches-table td:nth-child(n+6)').click(function() {
        
        var odd = $(this).data('odd');
        var matchID = $(this).closest('tr').data('matchid');    

        if ($(this).hasClass('selected-cell')) {
            $(this).removeClass('selected-cell');
            $(this).closest('tr').removeClass('selected-row');
            delete plays[matchID];
            sendAjax();
            
            return;
        }
        
        if ($(this).text() !== '') {
            $(this).addClass('selected-cell');
            $(this).closest('tr').addClass('selected-row');
            $(this).siblings().removeClass('selected-cell');

            plays[matchID] = odd;
            sendAjax();
        }
        
    });
    
    $('#ticket-send').click(function(e) {
       jsonString = JSON.stringify(plays);
       e.preventDefault();
       $.ajax({
           url: "php/init/ticketProcess.php",
           data: {data: jsonString},
           type: 'post',
           success: function(output) {
               console.log(output);
               cleanTicket();
               $('#ticket').css('visibility', 'hidden');
               location.reload();
           },
           error: function(e) {
               alert('e');
           }
       });
    });
    
    $('#ticket-clean').click(function(e) {
       e.preventDefault();
       cleanTicket();
    });
    
    function sendAjax() {
        jsonString = JSON.stringify(plays);
        $.ajax({
            url: "php/views/ticket.php",
            data: {data: jsonString},
            type: 'post',
            success: function(output) {
                $('#ticket-container').html(output);
                if (output.length !== 0) {
                    $('#ticket').css('visibility', 'visible');
                } else {
                    $('#ticket').css('visibility', 'hidden');
                }
            }
        });
    }
    
    function cleanTicket() {
       plays = {};
       $('.matches-table td').removeClass('selected-cell');
       $('.matches-table tr').removeClass('selected-row');
       sendAjax();
    }
});