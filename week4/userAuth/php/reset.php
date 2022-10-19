<?php
if (isset($_POST['submit'])) {
    $email = $_POST['email']; //complete this;
    $password = $_POST['password']; //complete this;

    resetPassword($email, $password);
}

function resetPassword($email, $password)
{
    //open file and check if the username exist inside
    $file = fopen('../storage/users.csv', 'ra+');

    if ($file) {
        while ($rowlist = fgetcsv($file)) {
            $error = [];
            if (count($rowlist) > 1) {
                //if it does, replace the password
                if ($rowlist[2] == $email) {
                    $rowlist[1] = $password;
                    fputcsv($file, $rowlist);
                    session_start();
                    $_SESSION['success'] = 'Password reset successfully';
                    session_write_close();
                    header("location:../forms/login.html");
                    exit();
                } else {
                    $error['error'] = 'User does not exist.';
                }
            }
        }
        if (!empty($error)) {
            $_SESSION['error'] = $error['error'];
        }
    } else {
        echo 'User does not exist.';
    }
}
