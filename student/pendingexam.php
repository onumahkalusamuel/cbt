<?php include("template.php"); 
    mycbtheader("Pending Exam");
?>
<!-- Submitting the questions -->
<!-- Getting the questions -->
<?php
	$student_id = $GLOBALS['student_id'];
	$result = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'paper/pendingexam.php?student_id='.$GLOBALS['student_id'] . '&class_id='.$GLOBALS['class_id']));
	if(@$result->code == 200) {
		$paper = $result->paper;
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
									Pending Exams
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">    
									<form action="./takeexam.php" method="POST">
										<div class="form-group col-md-3">
											<label>Examination Body</label>
											<select name="exam_body" class="form-control col-md-6">
												<option value="waec">WAEC</option>
												<option value="jamb">JAMB</option>
												<!-- <option value="neco">NECO</option> -->
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Examination Year</label>
											<select name="exam_year" class="form-control col-md-6">
												<!-- <option value="2014">2014</option> -->
												<option value="">All</option>
												<option value="2017">2017</option>
												<option value="2018">2018</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Subject</label>
											<select name="cat_id" class="form-control col-md-6">
												<option value="1">Mathematics</option>
												<option value="2">English Language</option>
												<option value="3">Chemistry</option>
												<option value="4">Physics</option>
												<option value="5">Biology</option>
												<option value="6">Geography</option>
												<option value="7">Literature in English</option>
												<option value="8">Economics</option>
												<option value="9">Commerce</option>
												<option value="10">Accounts</option>
												<option value="11">Government</option>
												<option value="12">CRK</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label>Paper Type</label>
											<select name="type" class="form-control col-md-6">
												<option value="obj">Objectives</option>
											</select>
										</div>
										<div class="form-group col-md-12">
											<button type="submit" class="form-control btn btn-success">Practice Now</button>
										</div>
									</form>
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