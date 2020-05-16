<?php
    session_start();
	// connect to the database
	$con = mysqli_connect('localhost', 'root', 'root', 'instastalking');
	
	// Retrieve posts from the database
	$posts = mysqli_query($con, "SELECT U.id_user, name, id_img, username, path, upload_date, likes
								FROM images I join users U ON I.id_user = U.id_user 
								ORDER BY upload_date desc");

	if (isset($_POST['post_comm'])) {
		$id_img = $_POST['id'];
		$id_user_logged = $_SESSION['id_user'];
		$comment = $_POST['comment'];
		$t = time();
		$data = date("Y-m-d",$t);
		$query = "INSERT INTO comments (id_user, id_img, comm, date)
				  VALUES('$id_user_logged', '$id_img', '$comment', '$data')";
		mysqli_query($con, $query);
		
		$scrollPos = (array_key_exists('scroll', $_GET)) ? $_GET['scroll'] : 0;
		header('Location: feed.php#scroll='.$scrollPos);
		exit;
	}


	if (isset($_POST['post_like'])) {
		$id_img = $_POST['id'];
		$id_user_logged = $_SESSION['id_user'];
		$likes = mysqli_query($con, "SELECT * FROM likes where id_user = '$id_user_logged' and id_img = '$id_img'");
		$like = mysqli_fetch_assoc($likes);
		
		$scrollPos = (array_key_exists('scroll', $_GET)) ? $_GET['scroll'] : 0; 
		
		if ($like) {
			$query = "UPDATE images set likes = likes - 1 Where id_img = '$id_img'";
			mysqli_query($con, $query);
			header('Location: feed.php');

			$query_delete = "DELETE from likes Where  id_user = '$id_user_logged' and id_img = '$id_img'";
			mysqli_query($con, $query_delete);
			header('Location: feed.php#scroll='.$scrollPos);
			exit;

		} else {
			$query = "INSERT INTO likes (id_user, id_img)
			VALUES('$id_user_logged', '$id_img')";
			mysqli_query($con, $query);

			$query_update = "UPDATE images set likes = likes + 1 Where id_img = '$id_img'";
			mysqli_query($con, $query_update);
			header('Location: feed.php#scroll='.$scrollPos);
			exit;
		}
	}
	
	if (isset($_POST['to_profile'])) {
		$_SESSION['profile_id_user'] = $_POST['profile_id_user'];
		header('Location: profile.php');
		exit;
	}
	
	if (isset($_POST['to_likers'])) {
		$_SESSION['likers_id_photo'] = $_POST['likers_id_photo'];
		header('Location: likers.php');
		exit;
	}
	
	if (isset($_POST['search_user'])) {
		$to_search = $_POST['searched_user'];
		
		$get_user_id = mysqli_query($con, "SELECT id_user
								FROM users
								WHERE username = '$to_search'");
		if ($user_id = mysqli_fetch_array($get_user_id)) {
			$user_id = $user_id['id_user'];
			$_SESSION['profile_id_user'] = $user_id;
			header('Location: profile.php');
			exit;
		}
		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="feed.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
			<form class="form-inline mr-auto" action="feed.php" method="post">
				<input id="myInput" name="searched_user" class="form-control" type="text" placeholder="Search" aria-label="Search">
				<button class="btn btn-mdb-color btn-rounded btn-sm my-0 ml-sm-2" name="search_user" hidden = "true" type="submit">Search</button>
			</form>
		</div>
		<div class="mx-auto order-0">
			<a class="navbar-brand mx-auto" href="#">InstaStalking <i class="fa fa-camera" aria-hidden="true"></i></a>
		</div>
		<div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" src="<?php $_SESSION['profile_id_user'] = $_SESSION['id_user']; $_SESSION['profile_username'] = $_SESSION['username']; ?>" href="profile.php">PROFILE</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?logout='1'">LOGOUT</a>
				</li>
			</ul>
		</div>
	</nav>

<?php while ($row = mysqli_fetch_array($posts)) { ?>


        <?php
        $id_img = $row['id_img'];
        $id_user = $row['id_user'];
        $nr_comm = mysqli_query($con, "SELECT COUNT(*) from comments where id_img = '$id_img'");
        $comm =  mysqli_query($con, "SELECT name, username, U.id_user, comm, date FROM instastalking.comments C join instastalking.users U on C.id_user = U.id_user where id_img = '$id_img'");

        $profile =  mysqli_query($con, "SELECT path FROM images where id_user ='$id_user' and profile ='1' ");
        $res = mysqli_fetch_array($profile);
        $nr_comments =mysqli_fetch_array($nr_comm);


        $id_user_logged = $_SESSION['id_user'];
        $likes = mysqli_query($con, "SELECT * FROM likes where id_user = '$id_user_logged' and id_img = '$id_img'");
        $like = mysqli_fetch_assoc($likes);
         ?>

        <div class="page-content page-container" id="page-content">
            <div class="padding">
                <div class="row container d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="box box-widget">
                            <div class="box-header with-border">
                                <div class="user-block">
									<img class="img-circle" src="<?php echo $res['0']; ?>" alt="User Image">
									<span class="username">
										<form action="feed.php" method="post">
											<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row['id_user'] ?>" >
											<button class="btn btn-primary" name="to_profile" onclick="submit" style="font-size:15px; margin-left:10px">
												<?php echo $row['name']; ?>
											</button>
										</form>
									</span>
									<span class="description">Public - <?php echo $row['upload_date']; ?></span>
								</div>

                            </div>
							
                            <div class="box-body">
								<img class="img-responsive pad" src="<?php echo $row['path']; ?>" alt="Photo">
                                <form action="feed.php" method="post">
								
                                <input type="text" hidden = "true"  name="id" value="<?php echo $row['id_img'] ?>" >
								<?php if($like) : ?>
									<button  class="btn btn-secondary data-remember-position" style="font-size:15px; margin-left:10px" name="post_like" type="submit">Unlike
										<i class="fa fa-thumbs-down">
										</i>
									</button>
									<span class="pull-right text-muted">
										<form action="feed.php" method="post">
											<input type="text" hidden = "true"  name="likers_id_photo" value="<?php echo $row['id_img'] ?>" >
											<button class="btn btn-primary data-remember-position" name="to_likers" onclick="submit" style="font-size:12px; margin-left:10px">
												<?php echo $row['likes']; ?> likes - <?php echo $nr_comments['0']; ?> comments
											</button>
										</form>
									</span>
                                <?php else : ?>
									<button  class="btn btn-secondary data-remember-position" style="font-size:15px; margin-left:10px" name="post_like" type="submit">Like
										<i class="fa fa-thumbs-up">
										</i>
									</button>
									<span class="pull-right text-muted">
										<form action="feed.php" method="post">
											<input type="text" hidden = "true"  name="likers_id_photo" value="<?php echo $row['id_img'] ?>" >
											<button class="btn btn-primary data-remember-position" name="to_likers" onclick="submit" style="font-size:12px; margin-left:10px">
												<?php echo $row['likes']; ?> likes - <?php echo $nr_comments['0']; ?> comments
											</button>
										</form>
									</span>
                                <?php endif; ?>
								</form>
                            </div>


                            <div class="box-footer box-comments" style="max-height: fit-content ; overflow:hidden;">

                                <?php while ($row1 = mysqli_fetch_array($comm) ) { ?>	
									<?php
										$username_comm = $row1['username'];
										$profile =  mysqli_query($con, "SELECT path
																		FROM images I join users U ON I.id_user = U.id_user
																		WHERE U.username = '$username_comm'
																		AND I.profile ='1'");
										$res = mysqli_fetch_array($profile);
									?>

									<div class="box-comment">
										<img class="img-circle img-sm" src="<?php echo $res['0']; ?>" alt="User Image">
										<div class="comment-text">
											<span class="username">
												<form action="feed.php" method="post">
													<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row1['id_user'] ?>" >
													<button class="btn btn-light btn-sm" name="to_profile" onclick="submit" style="font-size:15px">
														<?php echo $row1['name']; ?>
													</button>
												</form>
												<span class="text-muted pull-right">
													<?php echo $row1['date']; ?>
												</span>
											</span>
										<?php echo $row1['comm']; ?>
										</div>
									</div>
                                <?php } ?>		
							</div>
							
							<?php
								$usr = $_SESSION['username'] ;
								$profile =  mysqli_query($con, "SELECT path
																FROM images I join users U ON I.id_user = U.id_user
																WHERE U.username = '$usr'
																AND I.profile ='1' ");
								$res = mysqli_fetch_array($profile);
							?>

                            <div class="box-footer">
                                <form action="feed.php" method="post">
									<img class="img-responsive img-circle img-sm" src="<?php echo $res['0']; ?>" alt="Alt Text">
                                    <div class="img-push">
										<input id="myInput" name="comment" type="text" class="form-control input-sm" placeholder="Press enter to post comment">
									</div>
                                    <input type="text" hidden = "true"  name="id" value="<?php echo $row['id_img'] ?>" >
                                    <button name="post_comm" hidden = "true" onclick="submit" >Button</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php } ?>

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
