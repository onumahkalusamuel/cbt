<?php include("template.php"); 
	mycbtheader("Take Exam");
?>
<!-- Submitting the questions -->
<!-- Getting the questions -->

<?php
$questions;
	$url = $GLOBALS['config']['apibaseurl'] . 
	'question/takeexam.php?exam_year=' . 
	(!empty($_GET['exam_year']) ? $_GET['exam_year'] . '' : '') .
	(!empty($_GET['exam_body']) ? '&exam_body=' . $_GET['exam_body'] : '') .
	(!empty($_GET['type']) ? '&type=' . $_GET['type'] : '') .
	(!empty($_GET['cat_id']) ? '&cat_id=' . $_GET['cat_id'] : '');
	$result = json_decode(file_get_contents($url));
    if(@$result->code == 200) $questions = $result->questions;
    ?>
<?php if(empty($questions)): ?>
No Questions Found. Please contact admin.
<?php else: ?>
	<!-- Question heading  -->
	<div class="row text-center h4" style="font-weight:bold">
		<p> EXAMINATION BODY: <?=$result->exam_body;?></p>
		<p> EXAMINATION YEAR: <?=$result->exam_year;?></p>
		<p> SUBJECT: <?=$result->subject;?> &nbsp;&nbsp;&nbsp; DURATION: 60 Minutes</p>
	</div>
	<div class="pull-right text-center">TIME LEFT: <span class="" style="font-size:2em;border: 3px solid green; margin:10px; padding: 10px;"> <span id="mins">60</span> : <span id="secs">00 </span></span></div>
	<hr>
	<!-- Questions start -->
	<div class="row">
	<!-- <div class="h3 text-center text-success"> Questions </div> -->
		<form id="form" onsubmit="return false;" method="POST" action="./result.php">
			<div class="contain">
			<input hidden name="student_id" value="<?=$GLOBALS['student_id'];?>">
			<input hidden name="question_ids" value="<?=$result->question_ids;?>">
			<input hidden name="exam_body" value="<?=$result->exam_body;?>">
			<input hidden name="exam_year" value="<?=$result->exam_year;?>">
			<input hidden name="subject" value="<?=$result->subject;?>">
			<?php $x = 1; foreach($questions as $ques) : ?>
				<div class="eachquestiondiv" aria-id="q-<?=$x;?>" id="q-<?=$x;?>-div">
					<h4><?=$x;?>. 
					<?php if(!empty($ques->photo)): ?>
						<span class="">
							<img src="<?=@$config['baseurl'] .'images/question/' . $ques->photo;?>" style="max-height:200px"/>
						</span>
					<?php endif; ?>
					<?=$ques->question;?></h4>
					<div>
						<?php if(!empty($ques->option_a)) : ?> <p><input class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="A" /><label>&nbsp;&nbsp;A. <?=$ques->option_a;?></label></p> <?php endif;?>
						<?php if(!empty($ques->option_b)) : ?> <p><input class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="B" /><label>&nbsp;&nbsp;B. <?=$ques->option_b;?></label></p> <?php endif;?>
						<?php if(!empty($ques->option_c)) : ?> <p><input class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="C" /><label>&nbsp;&nbsp;C. <?=$ques->option_c;?></label></p> <?php endif;?>
						<?php if(!empty($ques->option_d)) : ?> <p><input class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="D" /><label>&nbsp;&nbsp;D. <?=$ques->option_d;?></label></p> <?php endif;?>
						<?php if(!empty($ques->option_e)) : ?> <p><input class="questionoption" name="que-<?=$ques->_id?>" type="radio" value="E" /><label>&nbsp;&nbsp;E. <?=$ques->option_e;?></label></p> <?php endif;?>
					</div>
				</div>
			<?php $x++; endforeach; ?>
				<hr>
				<!-- for the question numbers -->
				<div class="text-center h4">
					<?php $num = count($questions); ?>
					<?php for($x=1; $x<=$num; $x++): ?>
					<span style="padding: 15px 0px;display:inline-block">
						<span class="questionnumber" id="q-<?=$x;?>" style="border: 2px solid red; padding: 3px 10px; margin: 5px 2px; color:red; cursor: pointer"><?=$x;?></span>
						</span>
					<?php endfor; ?>
				</div>
				<hr>
				<p align="center"><input type="submit" class="btn btn-primary submitbutton" name="submitquestions" value="Submit" /></p>
			</div>
		</form>
		
		<div>&nbsp;</div>
		
		<div>&nbsp;</div>
	</div>
<?php endif; ?>

<!--  MathJax Controllers -->
<script src="../js/mathjax/MathJax.js"></script>
<script src="../js/mathjax/config.js"></script>
	<?php mycbtfooter(); ?>