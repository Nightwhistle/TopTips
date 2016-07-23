//if("<?php echo $_SESSION['timeZone']; ?>".length==0){

    var asdf = "<?php echo $_SESSION['timeZone']; ?>"
    alert(asdf);
    var userTime = new Date();
    var userTimeZoneOffset = -userTime.getTimezoneOffset();
    $.ajax({
        type: "GET",
        url: "./php/init/setTimeZone.php",
        data: "timeZoneOffset=" + userTimeZoneOffset,
        success: function(){
            location.reload();
        }
    });

//}