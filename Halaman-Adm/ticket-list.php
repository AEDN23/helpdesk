<?php
require_once("func.php");
include "header.php";
?>

<style>
    /* style untuk melebarkan table message dan problem solving disini */
    td:nth-child(8),
    td:nth-child(13) {
        width: 1000px;
    }
</style>
<div class="container">
    <div class="page-header">
        <h1>Ticket List</h1>
    </div>

    <div class="card mb-4 mt-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 1rem;">
                    <thead class="text-center">
                        <tr style="font-weight:bold;text-align: center;">
                            <td>NO</td>
                            <td>HELP</td>
                            <td>FULL NAME</td>
                            <td>SUBJECT</td>
                            <td>SERVICE</td>
                            <td>DEPARTMENT</td>
                            <td>PRIORITY</td>
                            <td>MESSAGE</td>
                            <td>STATUS</td>
                            <td>TIME START</td>
                            <td>TIME FINISH</td>
                            <td>DURATION</td>
                            <td>PROBLEM SOLVING</td>
                            <td>BEFORE</td>
                            <td>AFTER</td>
                            <td>ACTION</td>
                        </tr>
                    </thead>
                    <?php
                    $sql = "
                        SELECT * FROM tbl_ticket a 
                        LEFT JOIN tbl_user b ON b.tu_id=a.tt_user
                        LEFT JOIN tbl_department c ON c.td_id=a.tt_department
                        LEFT JOIN tbl_service d ON d.ts_id=a.tt_service
                        LEFT JOIN tbl_priority e ON e.tp_id=a.tt_priority
                        WHERE tt_status!='DELETE'
                        ORDER BY tt_id DESC
                    ";
                    $data = Q_array($sql);
                    foreach ($data as $key => $val) { ?>
                        <tbody>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $val['tt_no_id']; ?></td>
                                <td><?php echo $val['tu_full_name']; ?> <br> <?php echo $val['tu_email']; ?></td>
                                <td><?php echo $val['tt_subject']; ?></td>
                                <td><?php echo $val['ts_name']; ?></td>
                                <td><?php echo $val['td_name']; ?></td>
                                <td><?php echo $val['tp_name']; ?></td>
                                <td><?php echo $val['tt_message']; ?></td>
                                <?php
                                // Handle status and assign class
                                $statusClass = "status-default"; // Default class
                                switch ($val['tt_status']) {
                                    case 'NEW':
                                        $statusClass = "status-new";
                                        break;
                                    case 'PROCCESS':
                                        $statusClass = "status-process";
                                        break;
                                    case 'PENDING':
                                        $statusClass = "status-pending";
                                        break;
                                    case 'CANCEL':
                                        $statusClass = "status-cancel";
                                        break;
                                    case 'DONE':
                                        $statusClass = "status-done";
                                        break;
                                }
                                ?>
                                <td class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($val['tt_status']); ?></td>
                                <td><?php echo date("d F Y", strtotime($val['tt_created'])); ?><br><?php echo date("H:i:s A", strtotime($val['tt_created'])); ?></td>
                                <td><?php

                                    // Cek apakah $val['tt_updated'] ada dan tidak null

                                    if (!empty($val['tt_updated'])) {

                                        // Jika ada, konversi dan tampilkan tanggal dan waktu

                                        $timestamp = strtotime($val['tt_updated']);

                                        if ($timestamp !== false) {

                                            echo date("d F Y", $timestamp) . "<br>";

                                            echo date("H:i:s A", $timestamp);
                                        } else {

                                            // Jika format tanggal tidak valid

                                            echo "Format tanggal tidak valid.";
                                        }
                                    } else {

                                        // Jika $val['tt_updated'] null atau kosong, tampilkan kosong

                                        echo "-"; // Atau Anda bisa tidak menampilkan apa-apa

                                    }

                                    ?></td>
                                <td><?php echo $val['tt_duration']; ?></td>
                                <td><?php echo ($val['tt_problem_solving']); ?></td>
                                <td><canvas></canvas></td>
                                <td><canvas></canvas></td>
                                <td>
                                    <a class='btn btn-default' data-toggle='modal' data-target='#ed-<?php echo $key; ?>'>Option</a>
                                </td>
                            </tr>
                        </tbody>


                        <div id="ed-<?php echo $key; ?>" class="modal fade" role="dialog">
                            <div class="modal-dialog" style="background-color:#FFFFFF;">
                                <form class="modal-content" action="function.php" method="POST">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Change status <?php echo $val['tt_subject']; ?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-8 form-group">
                                                <label for="">Status:</label>
                                                <select name="status" class="form-control" required="true">
                                                    <option value="<?php echo $val['tt_status']; ?>">SELECTED : <?php echo $val['tt_status']; ?></option>
                                                    <option value="NEW">NEW</option>
                                                    <option value="PROCCESS">PROCCESS</option>
                                                    <option value="PENDING">PENDING</option>
                                                    <option value="CANCEL">CANCEL</option>
                                                    <option value="DONE">DONE</option>
                                                    <option value="DELETE">DELETE</option>
                                                </select>
                                                <br>
                                                <label for=""> Problem Solving:</label>
                                                <textarea class="form-control" name="problem-solving" placeholder="Problem solving"></textarea>
                                                <input type="hidden" name="id" value="<?php echo $val['tt_id']; ?>">

                                                <br>
                                                <label for="">Foto After:</label>
                                                <input type="file" name="tt_foto_after" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update-tl" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 10, // Default number of rows per page
                "lengthMenu": [10, 25, 50, 100] // Options for number of rows per page
            });
        });
    </script>
</div>


<?php include('footer.php'); ?>