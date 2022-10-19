<?php

session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email']; //finish this line
    $password = $_POST['password']; //finish this

    loginUser($email, $password);
}

function loginUser($email, $password)
{
    $file = fopen('users.csv', 'ra+');
    if ($file) {
        while ($row = fgetcsv($file)) {
            $error = [];
            if ($row[2] == $email && $row[1] == $password) {
                $_SESSION['username'] = $row[0];
                // echo "User logged in successfully.";
                session_write_close();
                header("location:../dashboard.php");
                exit();
            } else {
                $error['error'] = 'Wrong Credentials.';
            }
        }
        if (!empty($error)) {
            $_SESSION['error'] = $error['error'];
            session_write_close();
            header("location:../forms/login.html");
        }
    } else {
        echo "No record in the database";
    }
    /*
    Finish this function to check if username and password 
    from file match that which is passed from the form
    */
}

// echo "HANDLE THIS PAGE";
