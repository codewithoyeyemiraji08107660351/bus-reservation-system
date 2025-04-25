<?php

$error = 'Could not connect to database';

$con=mysqli_connect("localhost", "root", "oyeyemi", "bus_reservation");

if(!$con){
     die($error.mysqli_connect_error());
}
   

?>