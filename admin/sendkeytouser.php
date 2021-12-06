<?php
// Start a session for error reporting
session_start();
// Get our POSTed variables
$a1 = $_POST['a1'];
$a2 = $_POST['a2'];
$a3 = $_POST['a3'];




 $con = mysqli_connect("localhost","root","");
                                if (!$con)
                                    echo('Could not connect: ' . mysqli_error());
                                else
                                {
                                    mysqli_select_db( $con,"dataleakage");
	$q1="SELECT topic from presentation WHERE subject='$a3';";
	$key=mysqli_query($con,$q1);
	$res=mysqli_fetch_array($key);
	$digest=$res["topic"].$a1.strval(time());
	$hsh=hash('sha512',$digest,false);
	$sql = "UPDATE askkey SET k='$hsh',status='yes' WHERE filename='$a3'and user='$a1' ";
    $result = mysqli_query($con,$sql) or die ("Could not send data into DB: " . mysqli_error($con));
    $sql = "INSERT INTO record(subject,topic,sendto,time) VALUES ('" . $_POST["a3"] ."','" . $_POST["a2"] . "','" . 
							  $_POST["a1"] ."','".date("Y/m/d")."');";
						if (!mysqli_query($con,$sql))
							echo('Error : ' . mysqli_error($con));
						else
							echo '<script language="javascript">alert("Key has been successfully send!")</script>';
						
	header("Location: admin.php");
	exit;
								}
?>
