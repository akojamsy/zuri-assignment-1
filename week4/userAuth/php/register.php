<?php
if (isset($_POST['submit'])) {
    $username = clean_text($_POST['fullname']);
    $email = clean_text($_POST['email']);
    $password = clean_text($_POST['password']);

    $error = array();

    if (empty($username)) {
        $error['username'] = 'Username is required.';
    }
    if (empty($email)) {
        $error['email'] = 'email is required.';
    }
    if (empty($password)) {
        $error['password'] = 'password is required.';
    }
    if (!empty($error)) {
        session_start();
        $_SESSION['error'] = $error;
        session_write_close();
        header("location: ../forms/register.html");
    }

    registerUser($username, $email, $password);
}

function clean_text($string)
{
    $string = trim($string);
    $string = htmlspecialchars($string);
    $string = stripslashes($string);
    return $string;
}


function registerUser($username, $email, $password)
{
    //save data into the file
    $userdata = array("username" => $username, "password" => $password, "email" => $email);

    $file = fopen('../storage/users.csv', 'ra+');

    while ($row = fgetcsv($file)) {
        if ($row[2] == $email) {
            echo "User already exist.";
            exit();
        }
    }
    fputcsv($file, $userdata);
    fclose($file);
    echo "User successfully registered.";
    echo '<a href="../forms/login.html">Login</a> ';
}
