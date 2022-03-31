<?php
 session_start();
 if(!empty($_SESSION['student_id']) || !empty($_SESSION['class_id']) )  {
     header('Location: ./student/dashboard.php');
 }

if( isset($_POST['username']) && isset($_POST['password']) ) {
    $invalid = 'true';
    $config = include_once './config.php'; //for configuration
    $url = $config['apibaseurl'] . 'login.php?loginusername=' . $_POST['username'] . '&loginpassword=' . $_POST['password'];
    @$login = json_decode(file_get_contents($url));
    
    if(!empty($login)) {
        @session_start();
        foreach($login as $key => $value) $_SESSION[$key] = $value;
        header('Location: ./student/dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page - MY CBT EXAMS</title>
    <!-- Site for css handler -->
    <link rel="stylesheet" type="text/css" href="./csshandler.php?files=bootstrap.min.css,metisMenu.min.css,sb-admin-2.css,morris.css,font-awesome/css/font-awesome.min.css" />
    <!-- Site for js handler -->
    <script type="text/javascript" src="./jshandler.php?files=jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row text-center ">
        <br><br><br><br>
            <div class="col-md-12">
                <br /><br />
                <h2>MY CBT EXAMS</h2>
                <h5>( Please login to get access )</h5>
                 <br />
            </div>
        </div>
         <div class="row ">
                  <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                        <strong>   Enter Details To Login </strong>  
                            </div>
                            <div class="panel-body">
                                <?php if (!empty($invalid)) :?>
                                <div class="text-center alert alert-danger alert-dismissable" style="font-weight:bolder">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    Invalid Username or Password
                                </div>
                                <?php endif;?>
                                <form role="form" action="" method="POST">
                                       <br />
                                     <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                                            <input type="text" name="username" class="form-control" placeholder="Your Username " />
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                                            <input type="password" name="password" class="form-control"  placeholder="Your Password" />
                                        </div>
                                    <div class="form-group">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" /> Remember me
                                            </label>
                                        </div>
                                     
                                     <button type="submit" class="btn btn-primary">Login Now</button>
                                    <hr />
                                    No account? Contact Administrator 
                                    </form>
                            </div>
                           
                        </div>
                    </div>
        </div>
    </div>