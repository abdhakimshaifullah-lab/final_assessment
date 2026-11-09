<?php
require_once "auth.php";
require_login();
include "db.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $id = $_POST['Delete'];

    $sql = $conn->prepare("DELETE FROM results WHERE id = $id");

    if ($sql->execute()) {
        header("Location: index.php");
        exit;
    }
   
}
?>