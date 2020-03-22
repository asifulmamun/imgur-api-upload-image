<?php  
    echo "echo post method :   " . $_POST['link'];
    echo "<br>";
    
    session_start();
    $_SESSION['billing_image'] = $_POST['link'];
    echo "echo sesssion :   " .  $_SESSION['billing_image'];
    header("Location: custom-finished-upload.php");
?>