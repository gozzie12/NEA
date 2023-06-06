<?php
// db config
$con=mysqli_connect("localhost","root","pass","testy");
// Check connection
if (mysqli_connect_errno())
{
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>