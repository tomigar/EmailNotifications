<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: authentication/login.php");
	exit;
}
include 'backend/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Notifikácie</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="ajax/ajax.js"></script>
</head>

<body>
	<div class="container">
		<h1 class="my-5">Dobrý deň, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Vitajte na stránke.</h1>
		<p>
			<a href="authentication/reset-password.php" class="btn btn-warning">Resetovať heslo</a>
			<a href="authentication/logout.php" class="btn btn-danger ml-3">Odhlásiť sa</a>
		</p>
		<p id="success"></p>
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<!-- <h2>Správa <b>Služieb</b></h2> -->
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons"></i> <span>Pridať službu</span></a>
						<form method="post" action="backend/crud.php">
							<button class="btn btn-danger" name="delete_multiple" id="delete_multiple" type="submit"><i class="material-icons" type="submit"></i> <span>Odstrániť</span></button>
						</form>
					</div>
				</div>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
						<th>ID</th>
						<th>Meno zákazníka</th>
						<th>Email</th>
						<th>Názov</th>
						<th>Dátum expirácie</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody>

					<?php
					$db = new Database();
					$query = "SELECT * FROM services";
					$data = $db->getData($query);
					if ($data)
						foreach ($data as $row) {
					?>
						<tr id="<?php echo $row["id_service"]; ?>">
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" name="record[]" class="user_checkbox" data-user-id="<?php echo $row["id_service"]; ?>">
									<label for="checkbox2"></label>
								</span>
							</td>
							<td><?php echo $row["id_service"]; ?></td>
							<td><?php echo $row["customer_name"]; ?></td>
							<td><?php echo $row["customer_email"]; ?></td>
							<td><?php echo $row["service_name"]; ?></td>
							<td><?php echo $row["service_expiration"]; ?></td>
							<td>
								<a href="#editEmployeeModal" class="edit" data-toggle="modal">
									<i class="material-icons update" data-toggle="tooltip" data-id="<?php echo $row["id_service"]; ?>" data-name="<?php echo $row["customer_name"]; ?>" data-email="<?php echo $row["customer_email"]; ?>" data-service-name="<?php echo $row["service_name"]; ?>" data-expiration="<?php echo $row["service_expiration"]; ?>" title="Edit"></i>
								</a>
								<a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["id_service"]; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete"></i></a>
							</td>
						</tr>
					<?php
						}
					?>
				</tbody>
			</table>

		</div>
	</div>
	<!-- Add Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="user_form" action="backend/crud.php" method="post" name="add" value="add">
					<div class="modal-header">
						<h4 class="modal-title">Pridať Službu</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>MENO</label>
							<input type="text" id="name" name="customer_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>EMAIL</label>
							<input type="email" id="email" name="customer_email" class="form-control" required>
						</div>
						<div class="form-group">
							<label>NÁZOV</label>
							<input type="phone" id="service" name="service_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>DÁTUM EXPIRÁCIE</label>
							<input type="expiration" id="expiration" name="service_expiration" class="form-control" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" value="add" name="add">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" name="add" value="add" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="update_form" action="backend/crud.php" method="post" name="update" value="update">
					<div class="modal-header">
						<h4 class="modal-title">Edit User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="id_u" name="id" class="form-control" required>
						<div class="form-group">
							<label>Meno</label>
							<input type="text" id="name_u" name="customer_name_u" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="email" id="email_u" name="customer_email_u" class="form-control" required>
						</div>
						<div class="form-group">
							<label>SLUŽBA</label>
							<input type="text" id="service_name_u" name="service_name_u" class="form-control" required>
						</div>
						<div class="form-group">
							<label>EXPIRÁCIA</label>
							<input type="text" id="service_expiration_u" name="service_expiration_u" class="form-control" required>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" value="update" name="update">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" name="update" value="update" class="btn btn-info" id="update">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="delete_service" action="backend/crud.php" method="post" name="delete" value="delete">

					<div class="modal-header">
						<h4 class="modal-title">Delete User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="id_d" name="id" class="form-control">
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" class="btn btn-danger" id="delete" name="delete" value="delete">Delete</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>

</html>