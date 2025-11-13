<?php include('header.php');?>

<div class="container">
    <div class="page-header"><h1>Data Department</h1></div>
    
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create">Create New</button>
    <div id="create" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form class="modal-content" action="function.php" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Department</h4>
                </div>
                <div class="modal-body">                    
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" required="true">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">Description:</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save-dept" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr style="font-weight:bold;">
                    <td>NO</td>
                    <td>NAME</td>
                    <td>DESCRIPTION</td>
                    <td>ACTION</td>
                </tr>
                <?php 
                    $data = Q_array("SELECT * FROM tbl_department ORDER BY td_id DESC");
                    foreach ($data as $key => $val) {
                        echo "
                            <tr>
                                <td>".($key+1)."</td>
                                <td>".$val['td_name']."</td>
                                <td>".$val['td_description']."</td>
                                <td>
                                    <a class='btn btn-default' data-toggle='modal' data-target='#ed-".$key."'>Edit/Delete</a>
                                </td>
                            </tr>
                        ";
                ?>
                    <div id="ed-<?=$key;?>" class="modal fade" role="dialog">
                        <div class="modal-dialog" style="background-color:#FFFFFF;">
                            <form class="modal-content" action="function.php" method="POST">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Edit <?=$val['td_name'];?></h4>
                                </div>
                                <div class="modal-body">                    
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="">Name</label>
                                            <input type="text" name="name" class="form-control" required="true" value="<?=$val['td_name'];?>">
                                            <input type="hidden" name="id" value="<?=$val['td_id'];?>">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label for="">Description:</label>
                                            <textarea class="form-control" name="description"><?=$val['td_description'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update-dept" class="btn btn-default">Save Change</button>
                                    <button type="submit" name="delete-dept" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </table>
        </div>
    </div>
</div>


<?php include('footer.php');?>