$(document).ready(function() {
    $(".ticket-share").unbind().click(function(e) {
        e.preventDefault();
        var ticketid = $(this).data('ticketid');
        var ticketTableId = '#ticket-' + ticketid;
        
        html2canvas($(ticketTableId), {
        onrendered: function(canvas) {
            var dataURL = canvas.toDataURL();
            
            $.ajax({
                type: "POST",
                url: "./php/init/screenshotHandler.php",
                data: { 
                   imgBase64: dataURL,
                   fileName: ticketTableId
                }
            }).done(function(o) {
                console.log(o); 
            });
        }
    });
        
    });
    
});