<?php
    session_start();
	// connect to the database

	$id_user = $_SESSION['id_user'];
	$username = $_SESSION['username'];
	$con = mysqli_connect('localhost', 'root', 'root', 'instastalking');
    $posts = mysqli_query($con, "SELECT U.id_user,name,id_img, username, path, upload_date, likes FROM images I join users U on I.id_user = U.id_user
                           Where I.id_user = '$id_user' ORDER BY likes desc, upload_date desc");
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
  if(isset($_POST["upload"])) {
     $targetDir = "./Photos/";
     $fileName = basename($_FILES["file"]["name"]);
     $targetFilePath = $targetDir . $fileName;
     $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(!empty($_FILES["file"]["name"])) {

        if(in_array($fileType, $allowTypes)){
        // Upload file to server
             if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
                 $insert = $con->query("INSERT into images (id_user,path, upload_date) VALUES ($id_user,'".$fileName."', NOW())");
                if($insert){
                    $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                }
                 else{
                    $statusMsg = "File upload failed, please try again.";
                 }
            }
            else{
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        }
        else{
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
        }
    }
    else{
        $statusMsg = 'Please select a file to upload.';
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
             if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database

                $update = $con->query("UPDATE images set profile = 0 where profile = '1' and id_user = '$id_user'");
                $insert = $con->query("INSERT into images (id_user,path, upload_date,profile) VALUES ($id_user,'".$fileName."', NOW(), 1)");
                if($insert){
                    $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
                }
                 else{
                    $statusMsg = "File upload failed, please try again.";
                 }
            }
            else{
                $statusMsg = "Sorry, there was an error uploading your file.";
            }
        }
        else{
            $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
        }
    }
    else{
        $statusMsg = 'Please select a file to upload.';
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
                        </div>
                    <div class="mx-auto order-0">
                        <a class="navbar-brand mx-auto" href="feed.php">PHOTO APP <i class="fa fa-camera" aria-hidden="true"></i></a>
                    </div>
                <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">PROFILE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?logout='1'">LOGOUT</a>
                        </li>
                    </ul>
                </div>
            </nav>


    		<div class="container">

	    		<div class="row m-b-r m-t-3">

		    		<div class="" style="display:inline-flex"><img class="img-circle img" src="<?php echo $profile_img['0']; ?>" alt=""  style="width: 200px; margin-bottom: 20px;margin-top: 100px;margin-right: 100px;" >


				            <div class="col-md-9 p-t-2" style="margin-top: 60px; margin-left:-30px">

                                <h2 class="h2-responsive" style="margin-top: 60px;margin-left: 0px;"> @<?php echo $username ?> </h2>

                                <form action="profile.php" method="post" enctype="multipart/form-data">


                                <button name="upload_profile"class="btn btn-secondary" onclick="submit" >Change profile photo</button>

                                <button name="upload" class="btn btn-secondary" onclick="submit" >Upload new photo</button>
                                <input type="file" name="file" >
                                <br>
                                  <?php  echo $statusMsg;?>

                               </br>
                                </form>
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
        $nr_comm = mysqli_query($con, "SELECT COUNT(*) from comments where id_img = '$id_img'");
        $nr_comments =mysqli_fetch_array($nr_comm);
         ?>
            <div class="padding">

                <div class="row container d-flex justify-content-center">

                    <div class="col-md-6">
                           <h9>Posted on <?php echo $row['upload_date']; ?></h9>
                           <div class="box box-widget">

                            <div class="box-body"> <img class="img-responsive pad" src="<?php echo $row['path']; ?>" alt="Photo"></div>
                           <form method = "post" action = "profile.php">
                            <span class="pull-left text-muted" style="font-siez:10px;margin-left:20px;"><?php echo $row['likes']; ?> likes - <?php echo $nr_comments['0']; ?> comments</span>
                           <button class="btn btn-secondary pull-right" name="delete_post" onclick="submit" style="font-size:17px;margin-right:20px ;"><i class="fa fa-trash-o"></i></button>
                           <input type="text" hidden = "true"  name="id" value="<?php echo $row['id_img'] ?>" >
                           </form>
                         </div>
                     </div>
                 </div>
             </div>
          </div>




<?php } ?>

</body>
</html>
