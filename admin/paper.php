<?php include("template.php"); 
    mycbtheader("Paper");
?>
<?php
//process form submission
if(isset($_POST['createsubmit'])) {
    $data = json_encode($_POST);
    $opts = array(
        'http' => array(
            'method'    => 'POST',
            'header'    => 'Content-type: text/json',
            'content'   => $data
        )
    );
    $context = stream_context_create($opts);
    $Paper = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'paper/create.php', false, $context));

    if($Paper->code == 201) {
        $message['body'] = "Paper created successfully.";
        $message['alert'] = "success";
    } else {
        $message['body'] = "An error occured. Please make sure the Paper doesn't already exist.";
        $message['alert'] = "danger";
    }
}

if(isset($_POST['readsubmit'])) {
    print_r($_POST);
}

if(isset($_POST['updatesubmit'])) {
    print_r($_POST);
}

if(isset($_POST['deletesubmit'])) {
    if(!empty($_POST['id']))   {
        $data = json_encode($_POST);
        $opts = array(
            'http' => array(
                'method'    => 'DELETE',
                'header'    => 'Content-type: text/json',
                'content'   => $data
            )
        );
        $context = stream_context_create($opts);
        $Paper = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'paper/delete.php', false, $context));
        
        if($Paper->code == 200) {
            $message['body'] = "Paper deleted successfully.";
            $message['alert'] = "success";
        } else {
            $message['body'] = "An error occured. Please contact support.";
            $message['alert'] = "danger";
        }

    }
}

// load init data
$initdata = json_decode(file_get_contents($GLOBALS['config']['apibaseurl'].'paper/init.php'));

?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create Paper
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
                                <div class="form-group col-md-4">
                                    <label> Exam</label>
                                    <select class="form-control" name="exam_id">
                                        <?php foreach($initdata->exam as $exam) :?>
                                            <option value="<?=$exam->id;?>"> <?=$exam->title;?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                <label> Subject</label>
                                    <select class="form-control" name="subject_id">
                                        <?php foreach($initdata->subject as $subject) :?>
                                            <option value="<?=$subject->id;?>"> <?=$subject->title;?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                <label> Class</label>
                                    <select class="form-control" name="class_id">
                                        <?php foreach($initdata->class as $class) :?>
                                            <option value="<?=$class->id;?>"> <?=$class->title;?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary form-control" name="createsubmit" type="submit" Value="Submit" />   
                                </div>
                            </form>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Existing Papers
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table id="dataTables-example" class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>Class</th>
                                        <th>Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($initdata->paper as $key => $paper) :?>
                                    <tr>
                                        <td><?=($key+1);?>
                                        <td><?=$paper->exam;?></td>
                                        <td><?=$paper->subject;?></td>
                                        <td><?=$paper->class?></td>
                                        <td>
                                            <button title="Delete entry" class="delete btn btn-danger fa fa-trash-o" aria-value="<?=$paper->id;?>"></button>
                                            <button title="Update entry" class="update btn btn-primary fa fa-edit" aria-value="<?=$paper->id;?>"></button>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>                            
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

                    if(!confirm("This entry will be deleted permanently. Continue?")) return false;

                    form = document.createElement("form");
                    form.setAttribute('method','POST');
                    form.innerHTML = (`
                    <input name="deletesubmit" />
                    <input name="id" value="${element.target.getAttribute('aria-value')}" />`);
                    document.body.append(form);
                    form.submit();
                }

                document.querySelectorAll('.delete').forEach((element) => {
                    element.addEventListener('click', deleteEntry);
                });
            </script>
<?php mycbtfooter(); ?>