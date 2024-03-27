<?php
include './session.php';
include './db.php';

$getId_query = 'SELECT * FROM user WHERE username=?';
$getId_stmt = $connection->prepare($getId_query);
$getId_stmt->bind_param('s', $user);
$getId_stmt->execute();
$id_result = $getId_stmt->get_result();
$getId_stmt->close();

$id =  '';

while ($row = mysqli_fetch_assoc($id_result)) {
    $id = $row['id'];
};

echo $id;

$getUser_query = 'SELECT * FROM user WHERE id=?';
$getUser_stmt = $connection->prepare($getUser_query);
$getUser_stmt->bind_param('i', $id);
$getUser_stmt->execute();
$user_result = $getUser_stmt->get_result();

$username = $email = $password ='';
while ($row = mysqli_fetch_assoc($user_result)) {
    $email = $row['email'];
    $password = $row['password'];
    $username = $row['username'];
};

$getUser_stmt->close();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Welcome <?php echo $username?>!</h1>
    <div class="display-users">
            <h2>
                Your email is <?php echo $email; ?> and password <?php echo $password;?>
                . You id number is <?php echo $id?>.
            </h2>
            <a href="edit.php">Edit</a>
            <a href="logout.php">Logout</a>
            <a href="delete.php">Delete</a>
    </div>
</body>

</html>