<?php

$user = new User();
if (!$user->isLoggedIn()) {
    echo <<<END
    <div id="forms">
END;
            if (isset($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo "<p class='login-error'>$error</p>";
                }
            }
    echo <<<END
            <form id="login" action="php/init/loginHandler.php" method="post" name="login-form">
                <div><span>Username:</span><input type="text" name="login-username"></div>
                <div><span>Password:</span><input type="password" name="login-password"></div>
                <div><label for="login-remember-checkbox">Remember me</label><input type="checkbox" name="login-remember" id="login-remember-checkbox"></div>
                <div><input type="submit" id="login-submit" class="cpanel-link" name="login-submit" value="login"></div>
                <div><a href="#" id="forgot-password">Forgot password?</a></div>
            </form>
            <form id="register" action="php/init/registerHandler.php" method="post" name="register-form">
                <div><span>Username:</span><input type="text" name="register-username"></div>
                <div><span>Email:</span><input type="email" name="register-email"></div>
                <div><span>Password:</span><input type="password" name="register-password"></div>
                <div><span>Repeat password:</span><input type="password" name="register-password-repeat"></div>
                <div><input type="submit" id="register-submit" class="cpanel-link" name="register-submit" value="register"></div>
            </form>
    </div>
END;

} else {
    echo "<div id='cpanel'>";
        echo "<a href=\"php/init/logoutHandler.php\" class='cpanel-link'>Logout</a>";
        echo "<a href=\"#\" class='cpanel-link'>Profile</a>";
        echo "<a href=\"#\" class='cpanel-link'>Help</a>";
        require_once 'profile-settings.php';
    echo "</div>";
    
    
}