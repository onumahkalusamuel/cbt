<?php
if(empty($_GET['id']) || empty($_SERVER['HTTP_REFERER'])) die("Access denied");
?>
<?php include("template.php"); 
    mycbtheader("Result Full Details");
?>
<?php
if(!empty($_GET)) {
	$FullDetails = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'score/fulldetails.php?id='.$_GET['id']));
}
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
                           Details
                           <button class="btn btn-danger pull-right" onclick="window.close()"> Close </button>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">    
                            <div class="row text-center h4" style="font-weight:bold">
                            <p> EXAM BODY: <?=$FullDetails->exam_body;?></p>
                            <p> EXAM YEAR: <?=$FullDetails->exam_year;?> &nbsp;&nbsp;&nbsp;</p>
                            <p> SUBJECT: <?=$FullDetails->subject;?> &nbsp;&nbsp;&nbsp;</p>
                            <p> SCORE: <?=$FullDetails->score;?> &nbsp;&nbsp;&nbsp;</p>
                        </div>

                        <?php $x = 1; foreach($FullDetails->data as $full) : $ques= $full->details; ?>
                            <div style="border:5px solid <?=($full->status == 'success') ? 'green' : 'red' ?>; border-radius:10px;padding:10px;" class="border p-2">
                                <h3><?=$x;?>.
                                <?php if(!empty($ques->photo)): ?>
                                    <span class="">
                                        <img src="<?=@$config['baseurl'] .'images/question/' . $ques->photo;?>" style="max-height:200px"/>
                                    </span>
                                <?php endif; ?>
                                <?=$ques->question;?></h3>
                                <div>
                                    <?php if(!empty($ques->option_a)) : ?> <p><input disabled <?=($full->choice == 'A')? 'checked' : null; ?> class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="A" /><label>&nbsp;&nbsp;A. <?=$ques->option_a;?></label></p> <?php endif;?>
                                    <?php if(!empty($ques->option_b)) : ?> <p><input disabled <?=($full->choice == 'B')? 'checked' : null; ?> class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="B" /><label>&nbsp;&nbsp;B. <?=$ques->option_b;?></label></p> <?php endif;?>
                                    <?php if(!empty($ques->option_c)) : ?> <p><input disabled <?=($full->choice == 'C')? 'checked' : null; ?> class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="C" /><label>&nbsp;&nbsp;C. <?=$ques->option_c;?></label></p> <?php endif;?>
                                    <?php if(!empty($ques->option_d)) : ?> <p><input disabled <?=($full->choice == 'D')? 'checked' : null; ?> class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="D" /><label>&nbsp;&nbsp;D. <?=$ques->option_d;?></label></p> <?php endif;?>
                                    <?php if(!empty($ques->option_e)) : ?> <p><input disabled <?=($full->choice == 'E')? 'checked' : null; ?> class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="E" /><label>&nbsp;&nbsp;E. <?=$ques->option_e;?></label></p> <?php endif;?>
                                    <p class="text-success"> <label>Correct Answer: <?=strtoupper($ques->correct_answer);?></label></p>
                                    <?php if(!empty($ques->explanation)) : ?> 
                                        <hr>
                                        <h4>Explanation:</h4> <br> <span style="font-size:1.2em;"><?=$ques->explanation;?></span>
                                    <?php endif;?>
                                    <?php if(!empty($ques->answer_photo)): ?>
                                    <span class="">
                                        <img src="<?=@$config['baseurl'] .'images/question/' . $ques->answer_photo;?>" style="max-height:200px"/>
                                    </span>
                                <?php endif; ?>

                                </div>
                            </div>
                            <br>
                        <?php $x++; endforeach; ?>
                        <button class="btn btn-danger pull-right" onclick="window.close()"> Close </button>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
<!--  MathJax Controllers -->
<script src="../js/mathjax/MathJax.js"></script>
<script src="../js/mathjax/config.js"></script>


<?php mycbtfooter(); ?>