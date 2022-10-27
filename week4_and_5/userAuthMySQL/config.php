<?php


function db()
{
    //set your configs here
    $host = "127.0.0.1";
    $user = "root";
    $db = "zuriphp";
    $password = "";
    $conn = mysqli_connect($host, $user, $password, $db);
    if (!$conn) {
        echo "<script> alert('Error connecting to the database') </script>";
    }
    return $conn;
}


function create_table()
{
    $conn = db();
    $query = "CREATE TABLE IF NOT EXISTS students(

        id INT PRIMARY KEY AUTO_INCREMENT,
        full_names VARCHAR(50) NOT NULL,
        country VARCHAR(250) NOT NULL,
        password VARCHAR(250) NOT NULL, 
        email VARCHAR(250) NOT NULL,
        gender VARCHAR(25) NOT NULL,
        dob  DATE ,

        KEY full_names (full_names),
        KEY country (country),
        KEY email (email),
        KEY dob (dob)
    )";

    mysqli_query($conn, $query);
}

create_table();
