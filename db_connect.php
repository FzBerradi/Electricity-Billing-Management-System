<?php

$HOSTNAME ='localhost';
$USERNAME='root';
$PASSWORD= '';
$DATABASE='facturationel';
$port = '3306';

$con=mysqli_connect($HOSTNAME,$USERNAME,$PASSWORD,$DATABASE,$port);
if($con !== false){
    echo "";
}else{
    die("Connection failed: " . mysqli_connect_error());
}


?>