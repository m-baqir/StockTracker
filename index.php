<?php
session_start();

use StockTracker\Models\{Database, User};

if (isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (validpass($password) == FALSE)
        echo 'enter a valid password. A password must contain Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character';
    elseif (validpass($password) == TRUE) {
        $dbcon = Database::getDb();
        $u = new User();
        $userdetails = $u->getUser($username, $password, $dbcon);
        //if user is found in the db then create session variables and go to portfolio
        if ($userdetails !== NULL) {
            $userdetails->id = $_SESSION["userid"];
            $userdetails->wallet = $_SESSION["userwallet"];
            header("Location:portfolio.php");
        } else
            echo 'the username and password do not match. Please try again';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
}
//validates password against regex
function validpass($pass)
{
    $passex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    $pass_match = preg_match($passex, $pass);
    if ($pass === NULL || $pass === '' || $pass_match === 0) {
        return FALSE;
    } else
        return TRUE;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Login Page</title>
</head>

<body>
    <header>
    </header>


    <h4>Login Below</h4>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" />
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" />
        <input type="submit" name="login" id="login" value="Login" />
    </form>
    <form method="post">
        <input type="submit" name="logout" id="logout" value="Logout">
    </form>

    <footer>
    </footer>
</body>


</html>