<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <script src="js/core/jquery.js"></script>
        <script src="js/core/jquery-ui.min.js"></script>
        <script src="js/core/jsTimeZone.js"></script>
        <script src="js/core/stickyTableHeader.js"></script>
        <script src="js/core/html2canvas.js"></script>
        
        <link rel="stylesheet" type="text/css" href="css/reset.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/forms.css">
        <link rel="stylesheet" type="text/css" href="css/helpRegister.css">
        <link rel="stylesheet" type="text/css" href="css/matches.css">
        <link rel="stylesheet" type="text/css" href="css/livematches.css">
        <link rel="stylesheet" type="text/css" href="css/ticket.css">
        <link rel="stylesheet" type="text/css" href="css/profile.css">
        <link rel="stylesheet" type="text/css" href="css/toptips.css">
        <link rel="stylesheet" type="text/css" href="css/topplayers.css">
        <link rel="stylesheet" type="text/css" href="css/tables.css">
    </head>
    <body>
        <?php

        require_once './php/init/init.php';
        
        $user = new User();
        
        include_once './php/views/loginForm.php';
        
        if (!$user->isLoggedIn()) {
            include_once './php/views/helpRegister.html';
        }
        
        echo "<div id='top-players-list'></div>";
        
        echo "<div id=\"site-top\">";
            include_once './php/views/topTips.php';
            include_once './php/views/topPlayers.php';
        echo "</div>";
        
        if ($user->isLoggedIn()) {
        echo "<div id='site-content'>";
            echo "<div id=\"site-left\">";
                include_once './php/views/profile.php';
                include_once './php/views/activeTickets.php';
                include_once './php/views/finishedTickets.php';
                include_once './php/views/liveTable.php';
            echo "</div>";
            include_once './php/views/matchesTable.php';
            echo "<div id=\"ticket\">
                    <input id=\"ticket-clean\" type=\"submit\" value=\"x\">
                    <div id=\"ticket-container\"></div>
                    <input id=\"ticket-send\" class=\"cpanel-link\" type=\"submit\" value=\"Play\">
                  </div>";
            
            
        echo "</div>";
        }
        ?>
        
        <script src="js/ticketHandler.js"></script>
        <script src="js/finishedTicketsMoreButton.js"></script>
        <script src="js/topPlayersList.js"></script>
        <script src="js/tableSearch.js"></script>
        <script src="js/screenshot.js"></script>
    </body>
</html>
