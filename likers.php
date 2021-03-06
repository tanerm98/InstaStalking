<?php
    session_start();
	
	// connect to the database
	$id_img = $_SESSION['likers_id_photo'];
	
	$con = mysqli_connect('localhost', 'root', 'root', 'instastalking');
	
    $likers = mysqli_query($con, "SELECT id_user
								FROM likes
								WHERE id_img = '$id_img'");
								
	$no_result = 0;
	if (!$row = mysqli_fetch_array($likers)) {
		$no_result = 1;
	} else {
		$likers = mysqli_query($con, "SELECT id_user
								FROM likes
								WHERE id_img = '$id_img'");
	}
?>

<script>
function pop_up(url){
window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no') 
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="likers.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body style="background-image: url(./Photos/z.jpg); background-attachment: fixed; background-position: center; background-repeat: no-repeat; background-size: cover;">

	<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
		<div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
			<form class="form-inline mr-auto" action="feed.php" method="post">
				<input id="myInput" name="searched_user" class="form-control" type="text" placeholder="Search" aria-label="Search">
				<button class="btn btn-mdb-color btn-rounded btn-sm my-0 ml-sm-2" name="search_user" hidden = "true" type="submit">Search</button>
			</form>
		</div>
		<div class="mx-auto order-0">
			<a class="navbar-brand mx-auto" href="feed.php">InstaStalking <i class="fa fa-dashcube" aria-hidden="true"></i></a>
		</div>
		<div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" src="<?php $_SESSION['profile_id_user'] = $_SESSION['id_user']; $_SESSION['profile_username'] = $_SESSION['username']; ?>" href="profile.php"><i class="fa fa-id-card" aria-hidden="true"></i> PROFILE</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?logout='1'"><i class="fa fa-sign-out" aria-hidden="true"></i> LOGOUT</a>
				</li>
			</ul>
		</div>
	</nav>

<?php if ($no_result == 1) { ?>
	<img class="center" src="./Photos/no_results.jpeg" alt="Photo" style="display: block; margin-left: auto; margin-right: auto; width: 50%; opacity: 0.35; padding: 70px;">
<?php exit;} ?>

<nav class="navbar navbar-expand-md navbar-light d-flex justify-content-between" style="background-color: #e3f2fd; opacity: 0.5;">
	<div class="mx-auto order-0">
		<a class="navbar-brand mx-auto" href="feed.php">Likers <i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>
	</div>
</nav>

<div class="list-group" style="padding: 30px;">
<?php while ($row = mysqli_fetch_array($likers)) { ?>

        <?php
        $id_user = $row['id_user'];
        $user_data = mysqli_query($con, "SELECT name, username
										FROM users 
										WHERE id_user = '$id_user'");
		
		while ($row1 = mysqli_fetch_array($user_data) ) {
			$name = $row1['name'];
			$username = $row1['username'];
			$profile =  mysqli_query($con, "SELECT path
											FROM images I join users U ON I.id_user = U.id_user
											WHERE U.username = '$username'
											AND I.profile ='1'");
			$res = mysqli_fetch_array($profile);
		?>
			
		<div style="margin-left: auto; margin-right: auto;">
			<div style="padding: 10px;">
				<form action="followers.php" method="post">
					<img class="img-circle" src="<?php echo $res['0']; ?>" alt="User Image" style="border: 3px solid #dd9; width: 70px; height: 70px; margin-right: 10px;">
					<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row['id_user'] ?>" >
					<button class="btn btn-light btn-sm" name="to_profile" onclick="submit" style="font-size:15px">
						<?php echo $row1['name']; ?> - @<?php echo $row1['username']; ?>
					</button>
				</form>
			</div>
		</div>
		
		<?php } ?>		
		
<?php } ?>
</div>


<script>
var input = document.getElementById("myInput");
input.addEventListener("keyup", function(event) {
  if (event.keyCode === 13) {
   event.preventDefault();
   document.getElementById("myBtn").click();
  }
});
</script>

</body>
</html>
