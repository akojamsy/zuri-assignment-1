<?php
declare (strict_types = 1);

// $userdata = "hello \n";

$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$gender = $_POST['gender'];
$country = $_POST['country'];

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['gender']) && isset($_POST['country'])){
 $userdata = 'Name: ' . $name .', Email: ' . $email .', Date of Birth: ' .$date.', Gender: ' .$gender.', Country: ' .$country;
}

function openFile($filename, $userdata){
    $file = fopen($filename, 'w');
    $resource = fwrite($file, $userdata);
    fclose($file);
    $file_to_read = fopen($filename, 'r');
    $userdata_to_user = fread($file_to_read, filesize($filename));
    print_r($userdata_to_user);
    fclose($file_to_read);
}

openFile('userdata.csv', $userdata);