<?php
//require user configuration and database connection parameters
//Start PHP session
session_start();
//require user configuration and database connection parameters
require('config.php');
if (($_SESSION['logged_in']) == TRUE) {
//valid user has logged-in to the website
//Check for unauthorized use of user sessions
    $iprecreate = $_SERVER['REMOTE_ADDR'];
    $useragentrecreate = $_SERVER["HTTP_USER_AGENT"];
    $signaturerecreate = $_SESSION['signature'];
//Extract original salt from authorized signature
    $saltrecreate = substr($signaturerecreate, 0, $length_salt);
//Extract original hash from authorized signature
    $originalhash = substr($signaturerecreate, $length_salt, 40);
//Re-create the hash based on the user IP and user agent
//then check if it is authorized or not
    $hashrecreate = sha1($saltrecreate . $iprecreate . $useragentrecreate);
    if (!($hashrecreate == $originalhash)) {
//Signature submitted by the user does not matched with the
//authorized signature
//This is unauthorized access
//Block it
        header(sprintf("Location: %s", $forbidden_url));
        exit;
    }
//Session Lifetime control for inactivity
//Credits: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
    if ((isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessiontimeout))) {
        session_destroy();
        session_unset();
//redirect the user back to login page for re-authentication
        $redirectback = $domain . 'securelogin/';
        header(sprintf("Location: %s", $redirectback));
    }
    $_SESSION['LAST_ACTIVITY'] = time();
}
//Pre-define validation
$validationresults = TRUE;
$registered = TRUE;
$recaptchavalidation = TRUE;
//Trapped brute force attackers and give them more hard work by providing a captcha-protected page
$iptocheck = $_SERVER['REMOTE_ADDR'];
$iptocheck = mysql_real_escape_string($iptocheck);
if ($fetch = mysql_fetch_array(mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'"))) {
//Already has some IP address records in the database
//Get the total failed login attempts associated with this IP address
    $resultx = mysql_query("SELECT `failedattempts` FROM `ipcheck` WHERE `loggedip`='$iptocheck'");
    $rowx = mysql_fetch_array($resultx);
    $loginattempts_total = $rowx['failedattempts'];
    If ($loginattempts_total > $maxfailedattempt) {
//too many failed attempts allowed, redirect and give 403 forbidden.
        header(sprintf("Location: %s", $forbidden_url));
        exit;
    }
}
//Check if a user has logged-in
if (!isset($_SESSION['logged_in'])) {
    $_SESSION['logged_in'] = FALSE;
}
//Check if the form is submitted
if ((isset($_POST["pass"])) && (isset($_POST["user"])) && ($_SESSION['LAST_ACTIVITY'] == FALSE)) {
//Username and password has been submitted by the user
//Receive and sanitize the submitted information
    function sanitize($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysql_real_escape_string($data);
        return $data;
    }
    $user = sanitize($_POST["user"]);
    $pass = sanitize($_POST["pass"]);
//validate username
    if (!($fetch = mysql_fetch_array(mysql_query("SELECT `username` FROM `authentication` WHERE `username`='$user'")))) {
//no records of username in database
//user is not yet registered
        $registered = FALSE;
    }
    if ($registered == TRUE) {
//Grab login attempts from MySQL database for a corresponding username
        $result1 = mysql_query("SELECT `loginattempt` FROM `authentication` WHERE `username`='$user'");
        $row = mysql_fetch_array($result1);
        $loginattempts_username = $row['loginattempt'];
    }
    if (($loginattempts_username > 2) || ($registered == FALSE) || ($loginattempts_total > 2)) {
//Require those user with login attempts failed records to
//submit captcha and validate recaptcha
        require_once('recaptchalib.php');
        $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {
//captcha validation fails
            $recaptchavalidation = FALSE;
        } else {
            $recaptchavalidation = TRUE;
        }
    }
//Get correct hashed password based on given username stored in MySQL database
    if ($registered == TRUE) {
//username is registered in database, now get the hashed password
        $result = mysql_query("SELECT `password` FROM `authentication` WHERE `username`='$user'");
        $row = mysql_fetch_array($result);
        $correctpassword = $row['password'];
        $salt = substr($correctpassword, 0, 64);
        $correcthash = substr($correctpassword, 64, 64);
        $userhash = hash("sha256", $salt . $pass);
    }
    if ((!($userhash == $correcthash)) || ($registered == FALSE) || ($recaptchavalidation == FALSE)) {
//user login validation fails
        $validationresults = FALSE;
//log login failed attempts to database
        if ($registered == TRUE) {
            $loginattempts_username = $loginattempts_username + 1;
            $loginattempts_username = intval($loginattempts_username);
//update login attempt records
            mysql_query("UPDATE `authentication` SET `loginattempt` = '$loginattempts_username' WHERE `username` = '$user'");
//Possible brute force attacker is targeting registered usernames
//check if has some IP address records
            if (!($fetch = mysql_fetch_array(mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'")))) {
//no records
//insert failed attempts
                $loginattempts_total = 1;
                $loginattempts_total = intval($loginattempts_total);
                mysql_query("INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('$iptocheck', '$loginattempts_total')");
            } else {
//has some records, increment attempts
                $loginattempts_total = $loginattempts_total + 1;
                mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");
            }
        }
//Possible brute force attacker is targeting randomly
        if ($registered == FALSE) {
            if (!($fetch = mysql_fetch_array(mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'")))) {
//no records
//insert failed attempts
                $loginattempts_total = 1;
                $loginattempts_total = intval($loginattempts_total);
                mysql_query("INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('$iptocheck', '$loginattempts_total')");
            } else {
//has some records, increment attempts
                $loginattempts_total = $loginattempts_total + 1;
                mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");
            }
        }
    } else {
//user successfully authenticates with the provided username and password
//Reset login attempts for a specific username to 0 as well as the ip address
        $loginattempts_username = 0;
        $loginattempts_total = 0;
        $loginattempts_username = intval($loginattempts_username);
        $loginattempts_total = intval($loginattempts_total);
        mysql_query("UPDATE `authentication` SET `loginattempt` = '$loginattempts_username' WHERE `username` = '$user'");
        mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");
//Generate unique signature of the user based on IP address
//and the browser then append it to session
//This will be used to authenticate the user session
//To make sure it belongs to an authorized user and not to anyone else.
//generate random salt
        function genRandomString() {
//credits: http://bit.ly/a9rDYd
            $length = 50;
            $characters = "0123456789abcdef";
            for ($p = 0; $p < $length; $p++) {
                $string .= $characters[mt_rand(0, strlen($characters))];
            }
            return $string;
        }
        $random = genRandomString();
        $salt_ip = substr($random, 0, $length_salt);
//hash the ip address, user-agent and the salt
        $useragent = $_SERVER["HTTP_USER_AGENT"];
        $hash_user = sha1($salt_ip . $iptocheck . $useragent);
//concatenate the salt and the hash to form a signature
        $signature = $salt_ip . $hash_user;
//Regenerate session id prior to setting any session variable
//to mitigate session fixation attacks
        session_regenerate_id();
//Finally store user unique signature in the session
//and set logged_in to TRUE as well as start activity time
        $_SESSION['signature'] = $signature;
        $_SESSION['logged_in'] = TRUE;
        $_SESSION['LAST_ACTIVITY'] = time();
    }
}
if (!$_SESSION['logged_in']):
    ?>
    <!DOCTYPE HTML>
    <html>
        <head>
            <title>Login</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style type="text/css">
                .invalid {
                    border: 1px solid #000000;
                    background: #FF00FF;
                }
            </style>
        </head>
        <body >
            <h2>Restricted Access</h2>
            <br />
            Hi! This private website is restricted to public access. Please enter username and password to proceed.
            <br /><br />
            <!-- START OF LOGIN FORM -->
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

                Username:  <input type="text" class="<?php if ($validationresults == FALSE)
            echo "invalid"; ?>" id="user" name="user">
                Password: <input name="pass" type="password" class="<?php if ($validationresults == FALSE)
            echo "invalid"; ?>" id="pass" >
                <br /><br />
                <?php if (($loginattempts_username > 2) || ($registered == FALSE) || ($loginattempts_total > 2)) { ?>
                    Type the captcha below:
                    <br /> <br />
                    <?php
                    require_once('recaptchalib.php');
                    echo recaptcha_get_html($publickey);
                    ?>
                    <br />
                <?php } ?>
                <?php if ($validationresults == FALSE)
                        echo '<font color="red">Please enter valid username, password or captcha (if required).</font>'; ?><br />
                <input type="submit" value="Login">
            </form>
            <!-- END OF LOGIN FORM -->
            <br />
            <br />
            If you are not registered. You can register by clicking <a href="register.php">here</a>.
        </body>
    </html>
    <?php
    exit();
endif;
?>
 config.php
<?php
//This is a PHP Secure login system.
//require user configuration and database connection parameters
///////////////////////////////////////
//START OF USER CONFIGURATION/////////
/////////////////////////////////////
//Define MySQL database parameters
$username = "Your MySQL username";
$password = "Your MySQL password";
$hostname = "Your MySQL hostname";
$database = "Your MySQL database name";
//Define your canonical domain including trailing slash!, example:
$domain = "http://www.example.com/";
//Define sending email notification to webmaster
$email = 'youremail@example.com';
$subject = 'New user registration notification';
$from = 'From: www.example.com';
//Define Recaptcha parameters
$privatekey = "Your Recaptcha private key";
$publickey = "Your Recaptcha public key";
//Define length of salt,minimum=10, maximum=35
$length_salt = 15;
//Define the maximum number of failed attempts to ban brute force attackers
//minimum is 5
$maxfailedattempt = 5;
//Define session timeout in seconds
//minimum 60 (for one minute)
$sessiontimeout = 180;
////////////////////////////////////
//END OF USER CONFIGURATION/////////
////////////////////////////////////
//DO NOT EDIT ANYTHING BELOW!
$dbhandle = mysql_connect($hostname, $username, $password)
    or die("Unable to connect to MySQL");
$selected = mysql_select_db($database, $dbhandle)
    or die("Could not select $database");
$loginpage_url = $domain . 'securelogin/';
$forbidden_url = $domain . 'securelogin/403forbidden.php';
?>


The source code for any program binaries or compressed scripts that are
included with this program can be freely obtained at the following URL:

http://www.php-developer.org/php-secure-authentication-of-user-logins/
 logout.php
<?php
session_start();
require('config.php');
function sanitize($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
$signature = sanitize($_GET['signature']);
if ($signature === $_SESSION['signature']) {
//authenticated user request
    $_SESSION['logged_in'] = False;
    session_destroy();
    session_unset();
    echo 'You have successfully logout from the private website! Thank you.<br /><br />';
    ?>
    ===============<br />
    Navigation Menu<br />
    ===============<br />
    <a href="index.php">Homepage</a><br />
    <a href="about.php">About this page</a><br />
    <?php
} else {
//unauthorized logout
    header(sprintf("Location: %s", $forbidden_url));
    exit;
}
?>
 register.php
<?php
//require user configuration and database connection parameters
require('config.php');
//pre-define validation parameters
$usernamenotempty = TRUE;
$usernamevalidate = TRUE;
$usernamenotduplicate = TRUE;
$passwordnotempty = TRUE;
$passwordmatch = TRUE;
$passwordvalidate = TRUE;
$captchavalidation = TRUE;
//Check if user submitted the desired password and username
if ((isset($_POST["desired_password"])) && (isset($_POST["desired_username"])) && (isset($_POST["desired_password1"]))) {
//Username and Password has been submitted by the user
//Receive and validate the submitted information
//sanitize user inputs
    function sanitize($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysql_real_escape_string($data);
        return $data;
    }
    $desired_username = sanitize($_POST["desired_username"]);
    $desired_password = sanitize($_POST["desired_password"]);
    $desired_password1 = sanitize($_POST["desired_password1"]);
//validate username
    if (empty($desired_username)) {
        $usernamenotempty = FALSE;
    } else {
        $usernamenotempty = TRUE;
    }
    if ((!(ctype_alnum($desired_username))) || ((strlen($desired_username)) > 11)) {
        $usernamevalidate = FALSE;
    } else {
        $usernamevalidate = TRUE;
    }
    if (!($fetch = mysql_fetch_array(mysql_query("SELECT `username` FROM `authentication` WHERE `username`='$desired_username'")))) {
//no records for this user in the MySQL database
        $usernamenotduplicate = TRUE;
    } else {
        $usernamenotduplicate = FALSE;
    }
//validate password
    if (empty($desired_password)) {
        $passwordnotempty = FALSE;
    } else {
        $passwordnotempty = TRUE;
    }
    if ((!(ctype_alnum($desired_password))) || ((strlen($desired_password)) < 8)) {
        $passwordvalidate = FALSE;
    } else {
        $passwordvalidate = TRUE;
    }
    if ($desired_password == $desired_password1) {
        $passwordmatch = TRUE;
    } else {
        $passwordmatch = FALSE;
    }
//Hash the password
//This is very important for security reasons because once the password has been compromised,
//The attacker cannot still get the plain text password equivalent without brute force.
        function HashPassword($input) {
//Credits: http://crackstation.net/hashing-security.html
//This is secure hashing the consist of strong hash algorithm sha 256 and using highly random salt
            $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
            $hash = hash("sha256", $salt . $input);
            $final = $salt . $hash;
            return $final;
        }
        $hashedpassword = HashPassword($desired_password);
//Insert username and the hashed password to MySQL database
        mysql_query("INSERT INTO `authentication` (`username`, `password`) VALUES ('$desired_username', '$hashedpassword')") or die(mysql_error());
//Send notification to webmaster
        $message = "New member has just registered: $desired_username";
        mail($email, $subject, $message, $from);
//redirect to login page
        header(sprintf("Location: %s", $loginpage_url));
        exit;
    }
}
?>