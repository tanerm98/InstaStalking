<?php include('server.php') ?>
<!DOCTYPE html>
<html lang='en' class=''>
<head>
    <link rel="stylesheet" type="text/css" href="register.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
 <div class="container">
<div class="card bg-light">
<article class="card-body mx-auto" style="max-width: 400px;">
	<h4 class="card-title mt-3 text-center">Create Account</h4>

	<form method="post" action="register.php">
	<?php include('errors.php'); ?>
	<div class="form-group input-group">
		<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
		 </div>
        <input name="name" class="form-control" placeholder="Full name" type="text">
    </div> <!-- form-group// -->

        <div class="form-group input-group">
		<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
		 </div>
        <input name="username" class="form-control" placeholder="Username" type="text">
    </div> <!-- form-group// -->


    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
		 </div>
        <input name="email" class="form-control" placeholder="Email address" type="email">
    </div> <!-- form-group// -->


    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
		</div>
        <input name="password_1" class="form-control" placeholder="Create password" type="password">
    </div> <!-- form-group// -->


    <div class="form-group input-group">
    	<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
		</div>
        <input name="password_2" class="form-control" placeholder="Repeat password" type="password">
    </div> <!-- form-group// -->



    <div class="form-group">
        <button type="submit" name="reg_user" class="btn btn-primary btn-block"> Create Account  </button>
    </div> <!-- form-group// -->
    <p class="text-center">Have an account? <a href="login.php">Log In</a> </p>
</form>
</article>
</div> <!-- card.// -->
</div>
<!--container end.//-->


</body></html>
