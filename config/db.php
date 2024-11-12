<?php
    $conn = mysqli_connect('localhost', 'thong', 'QAZwsx2024!', 'user_info');
    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>