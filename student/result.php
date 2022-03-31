<?php include("template.php"); 
    mycbtheader("Exam Result");
?>
<?php
if(!empty($_POST)) {
	$data = json_encode($_POST);
    $opts = array(
        'http' => array(
            'method'    => 'POST',
            'header'    => 'Content-type: text/json',
            'content'   => $data
        )
    );
    $context = stream_context_create($opts);
	$MarkPaper = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'score/markpaper.php', false, $context));
	$message['body'] = $MarkPaper->message;
	$message['alert'] = $MarkPaper->code == 201 ? 'success' : 'info';
}

?>
<?php 
	// do the init things
	$initdata = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'score/resultinit.php?student_id='.$GLOBALS['student_id']));
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
                            Written Exams
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">    
						<table id="dataTables-example" width=100% class="table table-responsive">
							<thead>
								<tr>
									<th>#</th>
									<th>Exam Body</th>
									<th>Exam Year</th>
									<th>Subject</th>
									<th>Score</th>
									<th>Actions</th>
								</tr>
							</thead><tbody>
							<?php if(empty($initdata->score)) : ?>
								No exams written yet.
								<?php else : ?>
									<?php $x = 1; foreach($initdata->score as $score) : ?>
									<tr>
										<td><?=$x;?></td>
										<td><?=$score->exam_body;?></td>
										<td><?=$score->exam_year;?></td>
										<td><?=$score->subject;?></td>
										<td><?=$score->score;?></td>
										<td><button value="<?=$score->id;?>" class="btn btn-success fulldetails">See Full Details</button></td>
									</tr>
									<?php $x++; endforeach; ?>
									<?php endif; ?>
								</tbody>
						</table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

			<script>

			const fullDetails = (element) => {
				if(!!element.target.value) {
					let a = document.createElement("a");
					a.setAttribute('href', './fulldetails.php?id=' + element.target.value);
					a.setAttribute('target', 'blank');
					a.setAttribute('hidden', 'hidden');
					document.body.append(a);
					a.click();
				}
			}
			
			document.querySelectorAll(".fulldetails").forEach((element)=> {
				element.addEventListener("click", fullDetails);
			});
			</script>


<?php mycbtfooter(); ?>