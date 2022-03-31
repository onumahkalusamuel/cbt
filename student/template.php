<?php function mycbtheader($title=null) { 
    session_start();
    if(empty($_SESSION['student_id']) || empty($_SESSION['class_id']) )  {
        header('Location: ../login.php');
    }
    foreach($_SESSION as $key => $sess)  $GLOBALS[$key] = $sess;
    $GLOBALS['config'] = include_once('../config.php');
    $title = empty($title) ? "MyCBTExams" : $title;
    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> - My CBT Exams</title>    
    <!-- Site for css handler -->
    <link rel="stylesheet" type="text/css" href="../csshandler.php?files=bootstrap.min.css,metisMenu.min.css,sb-admin-2.css,morris.css,font-awesome/css/font-awesome.min.css" />
    <!-- Site for js handler -->
    <script type="text/javascript" src="../jshandler.php?files=jquery.min.js"></script>
</head>

<body>

    <div id="wrapper">
        
        <!--NAVIGATION-->
        <?php include('nav.template.php') ?>

  <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $title; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

<?php } ?>

<?php function mycbtfooter() { ?>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Javascript Handler -->
    <script type="text/javascript" src="../jshandler.php?files=bootstrap.min.js,metisMenu.min.js,sb-admin-2.js,jquery.dataTables.min.js,dataTables.bootstrap.min.js,dataTables.responsive.js"></script>

        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

</body>
</html>

<?php } ?>