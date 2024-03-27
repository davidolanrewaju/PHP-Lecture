<?php
session_start();
include './db.php';

//Form Validation
$username = $email = $password = '';
$errors = array();
function sanitizeData($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}
;

if (isset ($_POST["signup"])) {
    $username = sanitizeData($_POST["username"]);
    $email = sanitizeData($_POST["email"]);
    $password = sanitizeData($_POST["password"]);

    if (empty ($username) || empty ($email) || empty ($password)) {
        $errors["emptyInput"] = "Input field cannot be empty!";
    } else {
        if (!preg_match("/^[a-zA-Z-_]*$/", $username)) {
            $errors["nameErr"] = "Name can onlu contain letters, - and _";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["emailErr"] = "Invalid email format";
        }
        if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors['passwordErr'] = 'Password should contain at least one capital letter, number and special character';
        }
    }

    //Creat Operation - Add data to database
    if (empty ($errors)) {
        $query = 'INSERT INTO user(username, email, password)
                VALUE (?, ?, ?)';
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sss', $username, $email, $password);
        $stmt->execute();
        $stmt->close();
        $connection->close();

        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        // Redirect to welcome page or wherever you want
        header("Location: welcome.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css">
    <title>Document</title>
</head>

<body>
    <form action="signup.php" method="POST">
        <div class="inputcontainer">
            <label for="userbame">Username:</label>
            <input type="text" name="username" id="username"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["nameErr"]))
                    echo $errors["nameErr"]; ?>
            </span>
        </div>
        <div class="inputcontainer">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["emailErr"]))
                    echo $errors["emailErr"]; ?>
            </span>
        </div>
        <div class="inputcontainer">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["passwordErr"]))
                    echo $errors["passwordErr"]; ?>
            </span>
        </div>
        <input class="signup" type="submit" value="SignUp" name="signup">
    </form>
</body>

</html>