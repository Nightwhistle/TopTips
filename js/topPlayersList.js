$("#top-players-link").click(function() {
   $("#top-players-list").load("./php/views/helpers/topPlayersList.php").fadeIn(200);
});

$(document).ajaxComplete(function() {
    $("#top-players-list-close").click(function() {
        $("#top-players-list").fadeOut(200);
    });
    
    $(".top-players-list-row").unbind().click(function() {
        var playerid = $(this).data('player-id');
        var clicked = $(this);
        
        if (!clicked.hasClass('clicked')) {
            $.ajax({
                url: "./php/views/helpers/activeTicketsByUserId.php",
                data: {userid: playerid},
                type: "post",
                success: function(output) {
                    clicked.after(output);
                    $(".clicked").next().hide();
                    clicked.addClass('clicked');
                }
            });
        } else {
            $(".clicked").next().hide();
            $(".clicked").removeClass('clicked');
        }
    });
});


