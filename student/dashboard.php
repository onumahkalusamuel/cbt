<?php include("template.php"); 
    mycbtheader("Dashboard");
?>
<?php $a = ['red','green','danger','yellow'];?>


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
                                    Dashboard Menu
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body h3">    
                                    Welcome, <span style="font-weight:bold"> <?php echo $GLOBALS['student_name'];?> </span>
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