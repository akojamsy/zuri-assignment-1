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
        $fullnames = stripslashes($_REQUEST['fullnames']);
        if (empty($fullnames)) {
            $errors['fullnames'] = "Fullname is required.";
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
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO students (full_names, email, password, gender, country, dob ) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullnames, $email, $password, $gender, $country, $dob);

        if ($stmt->execute()) {
            echo "You have been successfully registered.";
        } else {
            echo "Error: " . $query . "<br>" . $conn->errors;
        }
    } else {
        session_start();
        $_SESSION['errors'] = $errors;
        session_write_close();
    }
}


//login users
function loginUser($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();

    // echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM students WHERE email =? LIMIT 1";
    $stm = $conn->prepare($query);
    $stm->bind_param('s', $email);
    $stm->execute();
    // $stm->bind_result($email);

    $result = mysqli_stmt_get_result($stm);

    if ($row = mysqli_fetch_assoc($result)) {
        //if it does, check if the password is the same with what is given
        $dbHashedPassword = $row['password'];
        $checkPassword = password_verify($password, $dbHashedPassword);
        if (!$checkPassword) {
            redirect("forms/login.html?error=wrongcredentials");
        } else {
            //if true then set user session for the user and redirect to the dasbboard
            session_start();
            $_SESSION['username'] = $row['full_names'];
            session_write_close();
            redirect('dashboard.php');
        }
    } else {
        echo 'Record does not exist in our database.';
    }

}


function resetPassword($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM students WHERE email =? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        //if it does, replace the password with $password given
        $id = $row['id'];
        $newPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE students SET password =? WHERE id =?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $newPassword, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Password has been successfully changed.";
        } else {
            echo "Something went wrong";
        }
    } else {
        echo "This email does not exist in our records.";
    }
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
                   <td style='width: 150px'>" . $data['full_names'] . "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . "</td> 
                    <td style='width: 150px'>" . $data['country'] . "</td>
                <form action='action.php' method='post'>
                    <input type='hidden' name='id' value=" . $data['id'] . ">" .
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>" .
            "</tr>
                </form>";
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
    $query = "DELETE FROM students WHERE id =? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "
        <style>
            .btn{
                border: 1px solid #ccc;
                background-color: #fcfc;
                color:#333;
                padding: 10px;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        <p> Record deleted successfully.</p><br>
        <a href='/zuri/week4_and_5/userAuthMySQL/dashboard.php' class='btn btn-primary'>Back</a>
        ";
    } else {
        echo "Something went wrong!";
    }
}
