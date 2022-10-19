<?php
session_start();

function logout()
{
    /*
Check if the existing user has a session
if it does
destroy the session and redirect to login page
*/
    if (isset($_POST['submit'])) {
        if (isset($_SESSION)) {
            session_destroy();
            header("location:../forms/login.html");
        }
    }
}

logout();
