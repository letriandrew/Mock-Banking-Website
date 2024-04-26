<?php
$db = new mysqli('localhost', 'u869039591_mockbankingdb', '1HNP6eSHfds@', 'u869039591_mockbankingdb');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$start_time = $_POST['start_time'];

$sql = "INSERT INTO user_activity (start_time) VALUES ('$start_time')";

if ($db->query($sql) === TRUE) {
    echo "Activity recorded successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $db->error;
}

$db->close();
?>
