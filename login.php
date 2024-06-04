<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
// }
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <title><?php echo $_SESSION['system']['name'] ?></title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body {
		width: 100vw;
		height: 100vh;
		position: fixed;
		top: 0;
		left: 0;
		background-image: url('assets/uploads/bg.png');
		background-size: cover;
		background-position: center;
		background-attachment: fixed;
		overflow-y: auto; /* Allows content to scroll if it exceeds the viewport height */
	}

	main#main {
		width: 100%;
		min-height: 100vh; /* Ensures at least full viewport height */
		display: flex;
		align-items: center; /* Vertically center the content in the main area */
		justify-content: center; /* Horizontally center the content in the main area */
	}


	/* Further responsive adjustments can be added as needed */
</style>

<body class="bg-dark">
	<main id="main">
		<div class="align-self-center w-100">
		<div class="login-wrapper"> 
			<div id="login-center" class="row justify-content-center">
				<div class="card col-md-4 col-lg-4">
					<div class="card-body py-5 px-4">
						<h4 class="text-dark text-center mb-0">
							<img src="assets/uploads/espressionist.png" class="mt-0" id="cafe-logo">
							<div class="divider mt-2"></div>
							<h3 class="text-center text-bold mb-4 mt-4" >EspressoInsights</h3>
						</h4>
						<form id="login-form">
							<div class="form-group">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text border-0"><i class="fa fa-user"></i></div>
									</div>
									<input type="text" id="username" name="username" class="form-control border-0" placeholder="Username" required>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text border-0"><i class="fa fa-lock"></i></div>
									</div>
									<input type="password" id="password" name="password" class="form-control border-0" placeholder="Password" required>
								</div>
							</div>
							<div class="form-check py-3">
								<input type="checkbox" class="form-check-input" id="exampleCheck1">
								<label class="form-check-label mt-1" for="exampleCheck1"> Remember me</label>
							</div>
							<center><button type="submit" class="btn col-md-4 btn-secondary" disabled>Login</button></center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</main>
	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
</body>

<script>
$(document).ready(function() {
    // Initially disable the login button
    $('#login-form button[type="submit"]').attr('disabled', true);

    // Enable the button when there's input in both the username and password fields
    $('#login-form input').on('input', function() {
        var isEmpty = $('#username').val().trim() === '' || $('#password').val().trim() === '';
        $('#login-form button[type="submit"]').attr('disabled', isEmpty);
    });

    // Handle form submission
    $('#login-form').submit(function(e){
        e.preventDefault();
        $('#login-form button[type="submit"]').attr('disabled', true).html('Logging in...');
        if($(this).find('.alert-danger').length > 0)
            $(this).find('.alert-danger').remove();
        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err);
                $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
            },
            success: function(resp) {
                if(resp == 1) {
                    location.href = 'index.php?page=home';
                } else if (resp == 0) {
                    $('#login-form').prepend('<div class="alert alert-danger">There is no Input.</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                } else {
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                }
            }
        });
    });
});
</script>

</html>
