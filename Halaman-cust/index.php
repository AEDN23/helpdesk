<?php include('header.php'); ?>

<div class="container">
    <div class="jumbotron">
        <img src="images/Logo HLP.png" height="100">

        <?php if (session_me()) { ?>
            <h2>Hello <?= ucfirst(strtolower($_SESSION['datauser']['tu_full_name'])); ?></h2>
            <p>Dibawah ini merupakan rangkuman total data yang terdapat pada aplikasi ini.</p>

            <br>
            <div class="row ">
                <div class="col-md-2">
                    <label>DEPARTMENT</label><br>
                    <label><?= Q_count("SELECT * FROM tbl_department"); ?> data</label>
                </div>
                <div class="col-md-2">
                    <label>PRIORITY</label><br>
                    <label><?= Q_count("SELECT * FROM tbl_priority"); ?> data</label>
                </div>
                <div class="col-md-2">
                    <label>SERVICE</label><br>
                    <label><?= Q_count("SELECT * FROM tbl_service"); ?> data</label>
                </div>
                <div class="col-md-2">
                    <label>USER</label><br>
                    <label><?= Q_count("SELECT * FROM tbl_user WHERE tu_role!='customer'"); ?> data</label>
                </div>
                <div class="col-md-2">
                    <label>TICKET</label><br>
                    <label><?= Q_count("SELECT * FROM tbl_ticket"); ?> data</label>
                </div>
            </div>
            <p><br></p>
            <p><br></p>
            <p></p>
            <p>
            <h2>Data Service</h2>
            </p>
            <div class="row mt-5">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr style="font-weight:bold;">
                            <td>NO</td>
                            <td>NAME</td>
                            <td>DESCRIPTION</td>
                        </tr>
                        <?php
                        $data = Q_array("SELECT * FROM tbl_service ORDER BY ts_id DESC");
                        foreach ($data as $key => $val) {
                            echo "
                            <tr>
                                <td>" . ($key + 1) . "</td>
                                <td>" . $val['ts_name'] . "</td>
                                <td>" . nl2br($val['ts_description']) . "</td>
                               
                            </tr>
                        ";
                        ?>
                            <div id="ed-<?= $key; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog" style="background-color:#FFFFFF;">
                                    <form class="modal-content" action="../Halaman-Adm/function.php" method="POST">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Edit <?= $val['ts_name']; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="">Name</label>
                                                    <input type="text" name="name" class="form-control" required="true" value="<?= $val['ts_name']; ?>">
                                                    <input type="hidden" name="id" value="<?= $val['ts_id']; ?>">
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="">Description:</label>
                                                    <textarea class="form-control" name="description"><?= $val['ts_description']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update-service" class="btn btn-default">Save Change</button>
                                            <button type="submit" name="delete-service" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <h2>Elastomix Helpdesk</h2>
            <p>---</p>
        <?php } ?>
    </div>

</div>


<?php include('footer.php'); ?>