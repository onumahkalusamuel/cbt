<?php include("template.php"); 
    mycbtheader("Profile");
?>

<?php if(isset($_POST['update'])) {
    $message['alert'] = "danger";
    extract($_POST);
    if(!empty($curpass))  {
        if ($newpass1 === $newpass2) {
            $body = array(
                'curpass' => $curpass,
                'id' => $GLOBALS['student_id'],
                'password' => $newpass1,
                'email' => $email,
                'phone' => $phone
            );

            if (isset($_FILES['photo'])) {
                $image = $_FILES['photo'];
                $ext = explode('.', $image['name']);
				$ext = $ext[count($ext)-1];
                $imagename = str_replace([' ',','], '', (strtolower($GLOBALS['student_name'].substr(uniqid(),5,5)) . '.' . $ext));
                $destination = dirname(dirname(__FILE__)) . '/images/profile/' . $imagename;
                //upload and add image to body array
			    if (move_uploaded_file($image['tmp_name'], $destination)) $body['photo'] = $imagename;
            }
            $opts = array(
                'http' => array(
                    'method'    => 'PUT',
                    'header'    => 'Content-Type: text/json',
                    'content'   => json_encode($body)
                )
            );
            $context = stream_context_create($opts);
            $update = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'student/update.php', false, $context));
            if($update->code == 200) $message['alert'] = 'success';
            $message['body'] = $update->message;
        } else {
            $message['body'] = 'New passwords do not match';
        }
    } else {
        $message['body'] = 'You must provide current password to make any changes.';
    }
	
	if(@$result->code == 200) {
		$questions = $result->questions;
		$paper_id = $result->paper_id;
	}
} ?>

<?php //profile init data
$student_id = $GLOBALS['student_id'];
$user = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'student/read.php?id='.$student_id));
if($user->code !== 200) {
    die("An error occured. Contact admin");
}
$user = $user->data[0];
?>
					<div class="row">
						<!-- For alert -->
						<?php if(isset($message)):?>
                            <div class="alert alert-<?=$message['alert'];?>">
                                <span style="font-weight:bold;"><?=$message['body']?></span> <span class="fa fa-times pull-right" onclick="this.parentElement.style.display = 'none'" style="cursor:pointer;"></span>
                            </div>
                         <?php endif;?>
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Profile
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">    

                                <div class="row">
                                    <div class="text-uppercase text-danger col-md-12"><b>
                                        <p>To make any changes, you must provide your current password.</p>
                                        <p>You can only make changes to your passport and password.</p></b>
                                    </div>

                                    <form class="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group col-md-12">
                                            <label for="photo">Profile Picture</label><br>
                                            <img height="150px" width="130px" class="" src="<?php if(!empty($user->photo)){echo "../images/profile/".$user->photo;}?>" />
                                            <input type="file" class="" name="photo" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="lastname" class="">Last Name</label>
                                            <input disabled class="form-control" value="<?=$user->lastname;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="firstname" class="">First Name</label>
                                            <input disabled class="form-control" value="<?=$user->firstname;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="middlename" class="">Middle (Other) Name</label>
                                            <input disabled class="form-control" value="<?=$user->middlename;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="regno" class="">Gender</label>
                                            <input disabled class="form-control" value="<?=$user->gender;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="regno" class="">Admission Number</label>
                                            <input disabled class="form-control" value="<?=$user->adm_no;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="">Class</label>
                                            <input disabled class="form-control" value="<?=$user->class;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="college" class="">House</label>
                                            <input disabled class="form-control" value="<?=$user->house;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="email" class="">Email</label>
                                            <input name="email" class="form-control" value="<?=$user->email;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="phone" class="">Phone</label>
                                            <input  name="phone" class="form-control" value="<?=$user->phone;?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="curpass" class="">Current Password</label>
                                            <input name="curpass" type="password" class="form-control" name="curpass"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="newpass1" class="">New Password</label>
                                            <input name="newpass1" type="password" class="form-control" name="newpass1"  />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="newpass2" class="">New Password Again</label>
                                            <input name="newpass2" type="password" class="form-control" name="newpass2" />
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="submit" class="btn btn-danger" value="Update" name="update" />
                                        </div>
                                    </form>
                                </div>
                                <!-- /.row -->

                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    <!-- /.row -->

<?php mycbtfooter(); ?>