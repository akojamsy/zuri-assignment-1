<?php
// This is where all helper functions will be added. 

function redirect($pages)
{
    header('Location:/zuri/week4_and_5/userAuthMySQL' . $pages);
    die;
}

// authenticate users
function authenticate($row)
{
    $_SESSION['USER'] = $row;
}

function is_logged_in()
{
    if (!empty($_SESSION['USER'])) return true;
    return false;
}

// utility functions
function old_value($key, $default = '')
{
    if (!empty($_POST[$key])) return $_POST[$key];
    return $default;
}
function old_checked_value($key, $default = '')
{
    if (!empty($_POST[$key])) return 'checked';
    return $default;
}
