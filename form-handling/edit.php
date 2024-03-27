<?php
include './session.php';
include './db.php';

//Get current data
$query = 'SELECT * FROM user WHERE username=?';
$stmt = $connection->prepare($query);
$stmt->bind_param('s', $user);
$stmt->execute();
$results = $stmt->get_result();

$prev_email = $prev_password = $id = '';

while ($row = mysqli_fetch_assoc($results)) {
    $prev_email = $row['email'];
    $prev_password = $row['password'];
    $id = $row['id'];
}
;

//Update current data
//Form Validation
$change_username = $change_email = $change_password = '';
$errors = array();
function sanitizeData($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}
;

if (isset ($_POST["update"])) {
    $change_username = sanitizeData($_POST["username"]);
    $change_email = sanitizeData($_POST["email"]);
    $change_password = sanitizeData($_POST["password"]);

    if (empty ($change_username) || empty ($change_email) || empty ($change_password)) {
        $errors["emptyInput"] = "Input field cannot be empty!";
    } else {
        if (!preg_match("/^[a-zA-Z-_]*$/", $change_username)) {
            $errors["nameErr"] = "Name can onlu contain letters, - and _";
        }
        if (!filter_var($change_email, FILTER_VALIDATE_EMAIL)) {
            $errors["emailErr"] = "Invalid email format";
        }
        if (!preg_match('/[a-z]/', $change_password) || !preg_match('/[A-Z]/', $change_password) || !preg_match('/[0-9]/', $change_password) || !preg_match('/[^a-zA-Z0-9]/', $change_password)) {
            $errors['passwordErr'] = 'Password should contain at least one capital letter, number and special character';
        }
    }

    // Update Operation - Add data to database
    if (empty ($errors)) {
        $update_query = 'UPDATE user SET username=?, email=?, password=? WHERE id=?';
        $update_stmt = $connection->prepare($update_query);
        $update_stmt->bind_param('sssi', $change_username, $change_email, $change_password, $id);
        $update_stmt->execute();
        $update_stmt->close();
        $connection->close();

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
    <title>Document</title>
</head>

<body>
    <h1>Edit your data</h1>
    <form action="edit.php" method="POST">
        <div class="inputcontainer">
            <label for="userbame">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $user ?>"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["nameErr"]))
                    echo $errors["nameErr"]; ?>
            </span>
        </div>
        <div class="inputcontainer">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $prev_email ?>"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["emailErr"]))
                    echo $errors["emailErr"]; ?>
            </span>
        </div>
        <div class="inputcontainer">
            <label for="password">Password:</label>
            <input type="text" name="password" id="password" value="<?php echo $prev_password ?>"> <br>
            <span>
                <?php if (isset ($errors["emptyInput"]))
                    echo $errors["emptyInput"]; ?>
                <?php if (isset ($errors["passwordErr"]))
                    echo $errors["passwordErr"]; ?>
            </span>
        </div>
        <input class="signup" type="submit" value="Update" name="update">
    </form>
</body>

</html>