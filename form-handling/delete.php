<?php 
include './session.php';
include './db.php';

if (isset($user)){
    $delete_query = 'DELETE FROM user WHERE username=?';
    $delete_stmt = $connection->prepare($delete_query);
    $delete_stmt->bind_param('s', $user);
    $delete_stmt->execute();
    
    header('Location: signup.php');

    $delete_stmt->close();
    $connection->close();
}
