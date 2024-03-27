<?php

session_start();

if (isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
}