<?php include("template.php"); 
    mycbtheader("Question");
?>
<?php
//process form submission
if(isset($_POST['readsubmit'])) {
	$result = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'question/read.php?paper_id='.$_POST['paper_id']));
	if($result->code == 200) {
		$questions = $result->questions;
        $paper_id = $result->paper_id;
        $related = $result->related;
	}
}

if(isset($_POST['updatesubmit'])) {
	//remove the button
	unset($_POST['updatesubmit']);
	// check for images
	if(!empty($_FILES)) {
		foreach($_FILES as $key => $image) {
			if(!empty($_POST[$key])) {
				$imagename = $_POST[$key];
			} else {
				$ext = explode('.', $image['name']);
				$ext = $ext[count($ext)-1];
				$imagename = uniqid('ques-') . '.' . $ext;
			}
			$destination = dirname(dirname(__FILE__)) . '/images/' . $imagename;

			if (move_uploaded_file($image['tmp_name'], $destination)) $_POST[$key] = $imagename;
		}
	}
	
	//then send the rest for processing
	$data = json_encode($_POST);
	$opts = array(
		'http' => array(
			'method'    => 'PUT',
			'header'    => 'Content-type: text/json',
			'content'   => $data
		)
	);
	$context = stream_context_create($opts);
	$Question = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'question/createupdate.php', false, $context));

	if($Question->code == 200) {
		$message['body'] = "Question(s) updated successfully. Errors: " . $Question->error;
		$message['alert'] = "success";
	} else {
		$message['body'] = "An error occured. Please contact support.";
		$message['alert'] = "danger";
	}
}

if(isset($_GET['deletesubmit'])) {
    if(!empty($_GET['id']))   {
        $opts = array(
            'http' => array(
                'method'    => 'DELETE',
                'header'    => 'Content-type: text/json',
                'content'   => json_encode($_POST)
            )
        );
        $context = stream_context_create($opts);
        die(file_get_contents($GLOBALS['config']['apibaseurl'].'question/delete.php', false, $context));
    }
}

// load init data
$initdata = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'question/init.php'));

?>

        <script type="text/javascript" src="../js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="../js/textinputs_jquery.js"></script>

        <!-- MathJax -->
		<script src="../js/mathjax/MathJax.js">
		  MathJax.Hub.Config({
			extensions: ['tex2jax.js',"TeX/AMSmath.js","TeX/AMSsymbols.js"],
			tex2jax: {inlineMath: [["$","$"],["$$","$$"],["\\(","\\)"]]},
			jax: ["input/TeX","output/NativeMML"],
			displayAlign: "center",
			displayIndent: "0.1em",
			showProcessingMessages: false
		  });
	  	</script>

        <script type="text/javascript" src="../js/writemaths.js"></script>
        
        <script language="javascript">
            $(document).ready(function() {
				$('.wm.side').writemaths();
			});
        </script>
        <div class="wm_preview"> </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Select Paper
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
                                <div class="form-group col-md-12">
                                    <label> Paper</label>
                                    <select class="form-control" name="paper_id">
                                        <?php foreach($initdata->paper as $paper) :?>
                                            <option value="<?=$paper->id;?>" <?=($paper->id==@$paper_id)? 'selected' :null;?>> <?=$paper->title;?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <input class="btn btn-primary form-control" name="readsubmit" type="submit" Value="Submit" />   
                                </div>
                            </form>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default questionspanel" style="display:<?=!empty($paper_id)?'block':'none';?>;">
                        <div class="panel-heading" style="text-align:center; font-weight:bold;">
                            QUESTIONS
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
						<form id="form2" method="POST" enctype="multipart/form-data">
							<input hidden name="paper_id" value="<?=@$paper_id;?>"/>
                            <!-- New Questions -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    New Questions&nbsp;&nbsp;<button class="btn btn-success addquestion fa fa-plus" onclick="return false;" value="0">&nbsp;&nbsp;ADD QUESTION</button>
                                    <div style="float:right; cursor:pointer">
                                        <span class="toggleexpansion" aria-value="new">&darr;&nbsp;&nbsp;expand</span>
                                    </div>
                                </div>
                                <div id="extraquestions" class="questionsection panel-body new collapse"></div>
                            </div>
                            <!-- Old Questions -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Old Questions
                                    <div style="float:right; cursor:pointer">
                                        <span class="toggleexpansion" aria-value="old">&darr;&nbsp;&nbsp;expand</span>
                                    </div>

                                </div>
                                <div class="questionsection panel-body old collapse">
                                    <?php if(empty($questions)): ?>
                                        <span class="noquestion"> No questions found. Please add questions.</span>
                                    <?php else: ?>
                                        <?php $x=1; foreach($questions as $ques): ?>
                                            <fieldset style="border:1px solid grey; border-radius:10px;padding:10px;" class="border p-2"> 
                                                <legend style="width:auto; padding: 0 10px; border:none" class="w-auto"> Question <?=$x;?></legend>
                                                <div class="form-group col-md-12">
                                                    <label>Question:</label>
                                                    <textarea name="old-<?=$ques->id;?>-question" class="form-control wm side"><?=$ques->question;?></textarea>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option A:</label>
                                                    <input name="old-<?=$ques->id;?>-opt_a" class="form-control wm side" value="<?=$ques->opt_a;?>" />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option B:</label>
                                                    <input name="old-<?=$ques->id;?>-opt_b" class="form-control wm side" value="<?=$ques->opt_b;?>" />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option C:</label>
                                                    <input name="old-<?=$ques->id;?>-opt_c" class="form-control wm side" value="<?=$ques->opt_c;?>" />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option D:</label>
                                                    <input name="old-<?=$ques->id;?>-opt_d" class="form-control wm side" value="<?=$ques->opt_d;?>" />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Option E:</label>
                                                    <input name="old-<?=$ques->id;?>-opt_e" class="form-control wm side" value="<?=$ques->opt_e;?>" />
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Correct Option:</label>
                                                    <select name="old-<?=$ques->id;?>-answer" class="form-control">
                                                        <option value="">--- Choose correct answer ---</option>
                                                        <option <?=($ques->answer)=="A"?"selected":null;?>>A</option>
                                                        <option <?=($ques->answer)=="B"?"selected":null;?>>B</option>
                                                        <option <?=($ques->answer)=="C"?"selected":null;?>>C</option>
                                                        <option <?=($ques->answer)=="D"?"selected":null;?>>D</option>
                                                        <option <?=($ques->answer)=="E"?"selected":null;?>>E</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Question image</label>
                                                    <input type="file" name="old-<?=$ques->id;?>-image" />
                                                    <input hidden value="<?=$ques->image;?>" name="old-<?=$ques->id;?>-image" />
                                                    <!-- Image preview if any -->
                                                    <?php if(!empty($ques->image) && is_file(dirname(dirname(__FILE__)) . '/images/' . $ques->image)): ?>
                                                    <span class="">
                                                        <img src="<?='../images/' . $ques->image;?>" style="width:70px; height:70px;"/>
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <button onclick="return false;" value="<?=$ques->id;?>" class="delete fa fa-trash-o fa-2x btn btn-lg btn-danger pull-right" title="Delete this question"></button></div>
                                            </fieldset>
                                            <br>
                                        <?php $x++; endforeach;?>
                                    <?php endif;?>
                                </div>
                            </div>
                            <!-- Related Questions -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Related Questions
                                    <div style="float:right; cursor:pointer">
                                        <span class="toggleexpansion" aria-value="related">&darr;&nbsp;&nbsp;expand</span>
                                    </div>
                                </div>
                                <div class="questionsection panel-body related collapse">
                                    <?php if(empty($related)): ?>
                                            <span class="noquestion"> No related found.</span>
                                        <?php else: ?>
                                        <table id="dataTables-example" class="table table-responsive" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Select</th>
                                                    <th>Question</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $x=1; foreach($related as $r): ?>
                                                    <tr>
                                                        <td><?=$x;?></td>
                                                        <td><input type="checkbox" class="form-control" name="related-<?=$r->id;?>-question"/></td>
                                                        <td><?=$r->question;?></td>
                                                    </tr>
                                            <?php $x++; endforeach;?>
                                        </tbody>
                                    </table>
                                        <?php endif;?>
                                </div>
                            </div>
							
							<div class="form-group text-center updatebutton" style="display:<?=!empty($questions)?'block':'none';?>;">
								<button name="updatesubmit" class="btn btn-success"> Submit</button>
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
            <script>
                const deleteEntry = (element) => {
					if(!confirm("This question will be deleted permanently. Continue?")) return false;
					fetch( ("<?=$GLOBALS['config']['apibaseurl'].'question/delete.php';?>"), {
						method: 'DELETE',
						headers: {
							'Accept': 'application/json, text/plain, */*',
							'Content-Type': 'application/json'
						},
						body: JSON.stringify({id:element.target.value})
					})
					.then(res => res.json())
					.then(response => {
						if( response.code == 200 ) {
							alert("Operation successful");
							element.target.parentElement.parentElement.outerHTML = "";
						} else {
							alert("An error occured. Please contact support.");
						}
					})
					.catch(err => {
						console.log({...err, ...{code: 500}});
					});
				}
				
				const addQuestion = (element) => {
					let x = 1 + (1 * element.target.value);
					let html = /*html*/`
					<fieldset style="border:1px solid grey; border-radius:10px;padding:10px;" class="border p-2"> 
								<legend style="width:auto; padding: 0 10px; border:none" class="w-auto"> New Question ${x}</legend>
								<div class="form-group col-md-12">
									<label>Question:</label>
									<textarea name="new-${x}-question" class="form-control wm side"></textarea>
								</div>
								<div class="form-group col-md-6">
									<label>Option A:</label>
									<input name="new-${x}-opt_a" class="form-control wm side"/>
								</div>
								<div class="form-group col-md-6">
									<label>Option B:</label>
									<input name="new-${x}-opt_b" class="form-control wm side"/>
								</div>
								<div class="form-group col-md-6">
									<label>Option C:</label>
									<input name="new-${x}-opt_c" class="form-control wm side"/>
								</div>
								<div class="form-group col-md-6">
									<label>Option D:</label>
									<input name="new-${x}-opt_d" class="form-control wm side"/>
								</div>
								<div class="form-group col-md-6">
									<label>Option E:</label>
									<input name="new-${x}-opt_e" class="form-control wm side"/>
								</div>
								<div class="form-group col-md-6">
									<label>Correct Option:</label>
									<select name="new-${x}-answer" class="form-control">
										<option value="">--- Choose correct answer ---</option>
										<option>A</option>
										<option>B</option>
										<option>C</option>
										<option>D</option>
										<option>E</option>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Question image</label>
									<input type="file" name="new-${x}-image" />
								</div>
							</fieldset><br>`;

					let a = document.createElement('div');
					a.innerHTML = html;
					document.querySelector("#extraquestions").prepend(a);
					element.target.value = x;
					//make sure the button is shown
					if ( (x == 1) && (document.querySelector(".updatebutton").style.display = "none") )
					document.querySelector(".updatebutton").style.display = "block";
                    $('.wm.side').writemaths();
					//remove the no question text
					if(document.querySelector(".noquestion").style.display = "block") 
					document.querySelector(".noquestion").style.display = "none";
                }
                
                const toggleExpansion = (element) => {
                    const toexpand = element.target.getAttribute("aria-value");
                    console.log(toexpand);
                    document.querySelectorAll('.toggleexpansion').forEach((element) => {
                        element.innerHTML = "&darr;&nbsp;&nbsp;expand";
                    });
                    document.querySelectorAll('.questionsection').forEach((element) => {
                        element.style.display = "none";
                    });
                    document.querySelector('.'+toexpand).style.display = "block";
                    element.target.innerHTML = "&uarr;&nbsp;&nbsp;collapse";
                }

                document.querySelectorAll('.delete').forEach((element) => {
                    element.addEventListener('click', deleteEntry);
                });
                document.querySelectorAll('.toggleexpansion').forEach((element) => {
                    element.addEventListener('click', toggleExpansion);
				});
				document.querySelector('.addquestion').addEventListener('click', addQuestion);
            </script>
<?php mycbtfooter(); ?>