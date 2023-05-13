<?php
    require ('database.php');
        $db = new Database();
        $conn = $db->conn;
       // Check connection
       if($conn === false){
           die("ERROR: Could not connect. "
               . mysqli_connect_error());
        }
// ADD NEW service
    if(!empty($_POST['add'])){
        $customer_name=$_POST['customer_name'];
		$customer_email=$_POST['customer_email'];
		$service_name=$_POST['service_name'];
		$service_expiration=$_POST['service_expiration'];
		$sql = "INSERT INTO `services`( `customer_name`, `customer_email`,`service_name`,`service_expiration`)
		VALUES ('$customer_name','$customer_email','$service_name','$service_expiration')";
    }
// UPDATE service
    elseif (!empty($_POST['update'])){
        $service_id=$_POST['id'];
        $customer_name_u=$_POST['customer_name_u'];
        $customer_email_u=$_POST['customer_email_u'];
        $service_name_u=$_POST['service_name_u'];
        $service_expiration_u=$_POST['service_expiration_u'];
        $sql = "UPDATE services SET customer_name='$customer_name_u', customer_email='$customer_email_u', service_name='$service_name_u', service_expiration='$service_expiration_u' WHERE id_service=$service_id";
    }
// DELETE service
    elseif (isset($_POST['delete'])){
        $service_id=$_POST['id'];
        $sql = "DELETE FROM services WHERE id_service=$service_id";
    }
// DELETE multiple
    elseif(isset($_POST['delete_multiple']))
    {

    }
		
    if (mysqli_query($conn, $sql))
    {
		echo json_encode(array("statusCode"=>200));
	}
	else
    {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
		mysqli_close($conn);
        echo '<script>window.location.href = "../index.php";</script>';

?>
