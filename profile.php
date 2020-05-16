<?php
    session_start();
	// connect to the database
	
	$id_user = $_SESSION['profile_id_user'];
	
	$con = mysqli_connect('localhost', 'root', 'root', 'instastalking');
	
    $get_username = mysqli_query($con, "SELECT username
								FROM users
								WHERE id_user = '$id_user'");
	$username = mysqli_fetch_array($get_username)['username'];
	
	$posts = mysqli_query($con, "SELECT U.id_user, name, id_img, username, path, upload_date, likes
								FROM images I join users U on I.id_user = U.id_user
								WHERE I.id_user = '$id_user'
								AND I.profile = 0
								ORDER BY upload_date desc");
						   
    $profile =mysqli_query($con, "SELECT path FROM images Where id_user = '$id_user' and profile = '1' ");
    $profile_img = mysqli_fetch_array($profile);

    $statusMsg="";

	if(isset($_POST["delete_post"])) {
		$id_img = $_POST['id'];
		$query_delete = "DELETE from images Where  id_user = '$id_user' and id_img = '$id_img'";
		mysqli_query($con, $query_delete);

		header('location: profile.php');
		exit;
	}
	
	if(isset($_POST["delete_comm"])) {
		$id_comm = $_POST['id_comm'];
		$con->query("DELETE from comments WHERE id_comm = '$id_comm'");

		header('location: profile.php');
		exit;
	}

  if(isset($_POST["upload"])) {
     $targetDir = "./Photos/";
     $fileName = basename($_FILES["file"]["name"]);
     $targetFilePath = $targetDir . $fileName;
     $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif');
    if(!empty($_FILES["file"]["name"])) {
        if(in_array($fileType, $allowTypes)) {
        // Upload file to server
             if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
                 $insert = $con->query("INSERT into images (id_user, path, upload_date) VALUES ($id_user,'".$fileName."', NOW())");
                if($insert){
                    $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                } else{
                    $statusMsg = "File upload failed, please try again.";
				}
            } else{
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        } else{
            $statusMsg = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed to upload.';
        }
    } else{
        $statusMsg = 'Please select a file to upload.';
    }
	header('location: profile.php');
  }


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

		$_SESSION['profile_id_user'] = $_POST['profile_id_user'];
		header('Location: profile.php#scroll='.$scrollPos);
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
			$_SESSION['profile_id_user'] = $_POST['profile_id_user'];
			header('Location: profile.php');

			$query_delete = "DELETE from likes Where  id_user = '$id_user_logged' and id_img = '$id_img'";
			mysqli_query($con, $query_delete);
			$_SESSION['profile_id_user'] = $_POST['profile_id_user'];
			header('Location: profile.php#scroll='.$scrollPos);
			exit;

		} else {
			$query = "INSERT INTO likes (id_user, id_img)
			VALUES('$id_user_logged', '$id_img')";
			mysqli_query($con, $query);

			$query_update = "UPDATE images set likes = likes + 1 Where id_img = '$id_img'";
			mysqli_query($con, $query_update);
			$_SESSION['profile_id_user'] = $_POST['profile_id_user'];
			header('Location: profile.php#scroll='.$scrollPos);
			exit;
		}
	}

if(isset($_POST["upload_profile"])) {
	$targetDir = "./Photos/";
	$fileName = basename($_FILES["file"]["name"]);
	$targetFilePath = $targetDir . $fileName;
	$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
	// Allow certain file formats
	$allowTypes = array('jpg','png','jpeg','gif','pdf');
	if(!empty($_FILES["file"]["name"])) {
		if(in_array($fileType, $allowTypes)){
		// Upload file to server
			 if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
			// Insert image file name into database

				$update = $con->query("UPDATE images set profile = 0 where profile = '1' and id_user = '$id_user'");
				$insert = $con->query("INSERT into images (id_user, path, upload_date, profile) VALUES ($id_user,'".$fileName."', NOW(), 1)");
				if($insert){
					$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
				} else{
					$statusMsg = "File upload failed, please try again.";
				}
			} else{
				$statusMsg = "Sorry, there was an error uploading your file.";
			}
		} else{
			$statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
		}
	} else{
		$statusMsg = 'Please select a file to upload.';
	}

	header('location: profile.php');
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
		$_SESSION['searched_user'] = $_POST['searched_user'];
		header('Location: searched.php');
		exit;
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
			<form class="form-inline mr-auto" action="profile.php" method="post">
				<input id="myInput" name="searched_user" class="form-control" type="text" placeholder="Search" aria-label="Search">
				<button class="btn btn-mdb-color btn-rounded btn-sm my-0 ml-sm-2" name="search_user" hidden = "true" type="submit">Search</button>
			</form>
		</div>
			<div class="mx-auto order-0">
				<a class="navbar-brand mx-auto" href="feed.php">InstaStalking <i class="fa fa-camera" aria-hidden="true"></i></a>
			</div>
		<div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="profile.php" src="<?php $_SESSION['profile_id_user'] = $_SESSION['id_user']; $_SESSION['profile_username'] = $_SESSION['username']; ?>">PROFILE</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?logout='1'">LOGOUT</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container">

		<div class="row m-b-r m-t-3">

			<div class="" style="display:inline-flex"><img class="img-circle img" src="<?php echo $profile_img['0']; ?>" alt=""  style="width: 100px; height: 100px; margin-bottom: 20px;margin-top: 70px;margin-right: 50px;" >
					<div class="col-md-9 p-t-2" style="margin-top: 60px; margin-left:-30px">
						<h2 class="h2-responsive" style="margin-top: 0px;margin-left: 0px;"> @<?php echo $username ?> </h2>
					
						<?php if($id_user == $_SESSION['id_user']) : ?>
							<form action="profile.php" method="post" enctype="multipart/form-data">
								<button name="upload_profile"class="btn btn-secondary" onclick="submit" >Change profile photo</button>
								<button name="upload" class="btn btn-secondary" onclick="submit" >Upload new photo</button>
								<input type="file" name="file">
								
								<?php  echo $statusMsg;?>
							</form>
						<?php endif; ?>
						
					</div>
				 </div>
			</div>
		</div>
	</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>
</div>


<?php while ($row = mysqli_fetch_array($posts)) { ?>


        <?php
        $id_img = $row['id_img'];
        $id_user = $row['id_user'];
        $nr_comm = mysqli_query($con, "SELECT COUNT(*) from comments where id_img = '$id_img'");
        $comm =  mysqli_query($con, "SELECT name, username, U.id_user, comm, id_comm, date FROM instastalking.comments C join instastalking.users U on C.id_user = U.id_user where id_img = '$id_img'");

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
									<span class="description">Public - <?php echo $row['upload_date']; ?></span>
								</div>

                            </div>
                            <div class="box-body">
								<img class="img-responsive pad" src="<?php echo $row['path']; ?>" alt="Photo">
                                <form action="profile.php" method="post">
								
                                <input type="text" hidden = "true"  name="id" value="<?php echo $row['id_img'] ?>" >
								<?php if($like) : ?>
									<button  class="btn btn-secondary" style="font-size:15px; margin-left:10px" name="post_like" type="submit">Unlike
										<i class="fa fa-thumbs-down">
										</i>
									</button>
									<span class="pull-right text-muted">
										<form action="profile.php" method="post">
											<input type="text" hidden = "true"  name="likers_id_photo" value="<?php echo $row['id_img'] ?>" >
											<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row['id_user'] ?>" >
											<?php if($id_user == $_SESSION['id_user']) : ?>
												<button class="btn btn-secondary pull-right" name="delete_post" onclick="submit" style="font-size:17px;margin-right:20px ;"><i class="fa fa-trash-o"></i></button>
											<?php endif; ?>
											<button class="btn btn-primary" name="to_likers" onclick="submit" style="font-size:12px; margin-left:10px">
												<?php echo $row['likes']; ?> likes - <?php echo $nr_comments['0']; ?> comments
											</button>
										</form>
									</span>
                                <?php else : ?>
									<button  class="btn btn-secondary" style="font-size:15px; margin-left:10px" name="post_like" type="submit">Like
										<i class="fa fa-thumbs-up">
										</i>
									</button>
									<span class="pull-right text-muted">
										<form action="profile.php" method="post">
											<input type="text" hidden = "true"  name="likers_id_photo" value="<?php echo $row['id_img'] ?>" >
											<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row['id_user'] ?>" >
											<?php if($id_user == $_SESSION['id_user']) : ?>
												<button class="btn btn-secondary pull-right" name="delete_post" onclick="submit" style="font-size:17px;margin-right:20px ;"><i class="fa fa-trash-o"></i></button>
											<?php endif; ?>
											<button class="btn btn-primary" name="to_likers" onclick="submit" style="font-size:12px; margin-left:10px">
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
												<form action="profile.php" method="post">
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
										<?php if($row1['id_user'] == $_SESSION['id_user']) : ?>
											<span class="text-muted pull-right">
												<form action="profile.php" method="post">
													<input type="text" hidden = "true"  name="id_comm" value="<?php echo $row1['id_comm'] ?>" >
													<button class="btn btn-light btn-sm" name="delete_comm" onclick="submit" style="font-size:10px">
														Delete
													</button>
												</form>
											</span>
										<?php endif; ?>
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
                                <form action="profile.php" method="post">
									<img class="img-responsive img-circle img-sm" src="<?php echo $res['0']; ?>" alt="Alt Text">
                                    <div class="img-push">
										<input id="myInput" name="comment" type="text" class="form-control input-sm" placeholder="Press enter to post comment">
									</div>
                                    <input type="text" hidden = "true"  name="id" value="<?php echo $row['id_img'] ?>" >
									<input type="text" hidden = "true"  name="profile_id_user" value="<?php echo $row['id_user'] ?>" >
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
