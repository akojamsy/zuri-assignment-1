<?php
// This is where all helper functions will be added. 

function redirect($pages)
{
    header('Location:/zuri/week4_and_5/userAuthMySQL/' . $pages);
    die;
}

// authenticate users
function authenticate($row)
{
    $_SESSION['USER'] = $row;
}

function is_logged_in()
{
    if (!empty($_SESSION['username'])) return true;
    return false;
}

