<?php

require_once "../config.php";
require_once "../functions.php";


//register users
function registerUser($fullnames, $email, $password, $gender, $country)
{

    //create a connection variable using the db function in config.php
    $conn = db();

    $errors = [];

    //check if user with this email already exist in the database
    if (empty($email)) {
        $errors['email'] = 'Email field is required.';
    } elseif (!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT id FROM students WHERE email='$email' LIMIT 1";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $errors['email'] = 'Email already exist!';
            }
        } else {
            $errors['email'] = 'Email is invalid. Please enter a valid email.';
        }
    }

    //checking for fullnames validity
    if (isset($fullnames)) {
        if (empty($fullnames)) {
            $errors['fullnames'] = "Fullname is required.";
        } elseif (!preg_match("/^[a-zA-Z]+$/", $fullnames)) {
            $errors['fullnames'] = "Fullname can only be letters and no spaces.";
        } elseif (strlen($fullnames) < 8) {
            $errors['fullnames'] = "Fullname cannot be less than 8 characters.";
        }
    }

    //checking for password validity
    if (empty($password)) {
        $errors['password'] = "Password field is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password should not be less than 8 characters.";
    }

    //checking for country validity
    if (empty($country)) {
        $errors['country'] = "Country field is required.";
    }

    //checking for gender validity
    if (empty($gender)) {
        $errors['gender'] = "Please choose gender.";
    }

    if (empty($errors)) {

        $dob = date('Y-m-d', strtotime('1993-3-12'));

        $stmt = $conn->prepare("INSERT INTO students (full_names, email, password, gender, country, dob ) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullnames, $email, $password, $gender, $country, $dob);

        if ($stmt->execute()) {
            echo "You have been successfully registered.";
        } else {
            echo "Error: " . $query . "<br>" . $conn->errors;
        }
    } else {
        $_SESSION['errors'] = $errors;
        redirect('/forms/register.php');
    }
}


//login users
function loginUser($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();

    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dasbboard
}


function resetPassword($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    //if it does, replace the password with $password given
}

function getusers()
{
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo "<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //show data
            echo "<tr style='height: 30px'>" .
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] .
                "</td> <td style='width: 150px'>" . $data['country'] .
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                "value=" . $data['id'] . ">" .
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>" .
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id)
{
    $conn = db();
    //delete user with the given id from the database
}
