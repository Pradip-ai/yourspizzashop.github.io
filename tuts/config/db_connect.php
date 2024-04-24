<?php 

// Connect to the database
$conn = mysqli_connect('localhost', 'pradip', 'Test1234', 'your_pizza');

// Check the connection
if (!$conn) {
    echo 'Connection Error: '. mysqli_connect_error();
}

?>