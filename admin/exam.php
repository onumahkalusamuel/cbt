<?php include("template.php"); 
    mycbtheader("Exam");
?>
<?php
//process form submission
if(isset($_POST['submit'])) {

    if(!empty($_POST['title']))   {
        $data = json_encode($_POST);
        $opts = array(
            'http' => array(
                'method'    => 'POST',
                'header'    => 'Content-type: text/json',
                'content'   => $data
            )
        );
        $context = stream_context_create($opts);
        $exam = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'exam/create.php', false, $context));
        

        if($exam->code == 201) {
            $message['body'] = "Exam created successfully.";
            $message['alert'] = "success";
        } else {
            $message['body'] = "An error occured. Please make sure the exam doesn't already exist.";
            $message['alert'] = "danger";
        }

    }
}
?>
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create Exam
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- For alert -->
                            <?php if(isset($message)):?>
                            <div class="alert alert-<?=$message['alert'];?>">
                                <span style="font-weight:bold;"><?=$message['body']?></span> <span class="fa fa-times pull-right" onclick="this.parentElement.style.display = 'none'" style="cursor:pointer;"></span>
                            </div>
                            <?php endif;?>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <input class="form-control" name="title" type="text" placeholder="Exam Title (e.g. 2019/2020 2nd Term First C.A.)" required />
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary form-control" name="submit" type="submit" Value="Submit" />   
                                </div>
                            </form>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

<?php mycbtfooter(); ?>