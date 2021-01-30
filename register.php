<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");
$account = new Account($conn);
include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");

function getInputUser($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register to Spotify!</title>
    <link rel="shortcut icon" href="./assets/images/icons/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/register.css">
</head>

<body>
    <?php
    if (isset($_POST['registerButton'])) {
        echo "<script>document.addEventListener('DOMContentLoaded',(e) => {
                document.getElementById('loginForm').style.display = 'none'
                    document.getElementById('RegisterForm').style.display = 'block'   
            })</script>";
    }else{
        echo "<script>document.addEventListener('DOMContentLoaded',(e) => {
            document.getElementById('loginForm').style.display = 'block'
                document.getElementById('RegisterForm').style.display = 'none'   
        })</script>";
    }

    ?>
    <div id="background">
        <div id="loginContainer">
            <div id="inputContainer">
                <form action="register.php" id="loginForm" method="POST">
                    <p id="spotifyIcon"><img src="./assets/images/icons/icon.png" alt="spotify_icon"></p>
                    <p id="spotifyHead">Spotify<span>@music</span></p>
                    <h2>Login to your account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$loginError); ?>
                        <label for="loginUsername">Username</label>
                        <input type="text" id="loginUsername" name="loginUsername" placeholder="e.g. Hacktivist" value="<?php getInputUser('loginUsername'); ?>" required>
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required>
                    </p>
                    <button type="submit" name="loginButton">LOG IN</button>
                    <div class="accountChanged">
                        <span id="hideLogin">
                            Don't have an account yet? Signup here.
                        </span>
                    </div>
                </form>

                <form action="register.php" id="RegisterForm" method="POST">
                    <h2>Create your free account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$userName); ?>
                        <?php echo $account->getError(Constants::$checkUsernameResult); ?>
                        <label for="Username">Username</label>
                        <input type="text" id="Username" name="Username" placeholder="e.g. Hacktivist" value="<?php getInputUser('Username'); ?>" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$firstName); ?>
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" placeholder="First Name" value="<?php getInputUser('firstName'); ?>" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$lastname); ?>
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" value="<?php getInputUser('lastName'); ?>" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$email1); ?>
                        <?php echo $account->getError(Constants::$email2); ?>
                        <?php echo $account->getError(Constants::$checkEmailResult); ?>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" value="<?php getInputUser('email'); ?>" required>
                    </p>
                    <p>
                        <label for="confirmEmail">Confirm Email</label>
                        <input type="email" id="confirmEmail" name="confirmEmail" placeholder="Confirm Email" value="<?php getInputUser('confirmEmail'); ?>" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$pass1); ?>
                        <?php echo $account->getError(Constants::$pass2); ?>
                        <?php echo $account->getError(Constants::$pass3); ?>
                        <label for="Password">Password</label>
                        <input type="password" id="Password" name="Password" placeholder="Password" required>
                    </p>
                    <p>
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                    </p>
                    <button type="submit" name="registerButton">SIGN UP</button>
                    <div class="accountChanged">
                        <span id="hideRegister">
                            Already have an account? Login here.
                        </span>
                    </div>
                </form>
            </div>

            <div id="loginText">
                <h1>Get great music, right now</h1>
                <h2>Listen loads of songs for free.</h2>
                <ul>
                    <li>Discover music you'll fall in love with</li>
                    <li>Create your own playlists</li>
                    <li>Follow artists to keep up to date</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="assets/js/register.js"></script>
</body>

</html>