<?php
session_start();
require_once("func.php");

if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    // Include database connection and any necessary functions

    // Get the parameters
    $k_start = Q_mres($_GET['start']);
    $k_end = Q_mres($_GET['end']);
    $q_status = "AND tt_status!='DELETE'";

    if (isset($_GET['status']) && $_GET['status'] !== "ALL") {
        $k_status = Q_mres($_GET['status']);
        $q_status = "AND tt_status='$k_status'";
    }

    // Prepare the SQL query
    $sql = "
        SELECT * FROM tbl_ticket a
        LEFT JOIN tbl_user b ON b.tu_id=a.tt_user
        LEFT JOIN tbl_department c ON c.td_id=a.tt_department
        LEFT JOIN tbl_service d ON d.ts_id=a.tt_service
        LEFT JOIN tbl_priority e ON e.tp_id=a.tt_priority
        WHERE (DATE_FORMAT(tt_created, '%Y-%m-%d') BETWEEN '$k_start' AND '$k_end') " . $q_status . " 
        ORDER BY tt_id DESC
    ";

    $data = Q_array($sql);

    // Set headers for Excel file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="report.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Output the table headers
    echo "NO\tFULL NAME\tSUBJECT\tSERVICE\tDEPARTMENT\tPRIORITY\tSTATUS\tTIME\n";

    // Output the data
    if (!empty($data)) {
        foreach ($data as $key => $val) {
            echo ($key + 1) . "\t" . $val['tu_full_name'] . " <br> " . $val['tu_email'] . "\t" . $val['tt_subject'] . "\t" . $val['ts_name'] . "\t" . $val['td_name'] . "\t" . $val['tp_name'] . "\t" . $val['tt_status'] . "\t" . date("d F Y H:i:s A", strtotime($val['tt_created'])) . "\n";
        }
    } else {
        echo "No data!\n";
    }
    exit; // Stop further execution
}




?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="20">
    <title>HELPDESK</title>
    <link rel="shortcut icon" href="images/icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">





    <style>
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #222;
            /* Sesuaikan warna background */
            color: #fff;
            /* Sesuaikan warna teks */
            text-align: center;
            /* Untuk menyelaraskan teks di tengah */
            padding: 2px 0;
            /* Berikan padding */
            z-index: 999;
            /* Supaya selalu di atas elemen lain */
        }

        .status-new {

            color: orange;
            font-weight: bold;
        }

        .status-process {

            color: blue;
            font-weight: bold;
        }


        .status-pending {

            color: black;
            font-weight: bold;

        }

        .status-cancel {

            color: red;
            font-weight: bold;

        }

        .status-done {

            color: green;
            font-weight: bold;

        }

        td:nth-child(8),
        td:nth-child(13) {
            width: 1000px;
        }
    </style>



    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the current printer page size */
            margin: 0mm;
            /* this affects the margin in the printer settings */
        }

        .print-hide {
            display: none;
        }

        .print-header {
            font-size: 15px;
        }

        .print-container {
            font-size: 10px;
        }
    </style>
</head>

<?php
// if (session_status() !== PHP_SESSION_ACTIVE) {
if (!session_me()) {
    if (your_position() !== site_url(true)) {
        redirect_to(site_url(true));
    }
    echo "
            <script>
                $(window).load(function(){        
                    $('#myModal').modal('show');
                }); 
            </script>
        ";
}
?>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= site_url(); ?>">HELPDESK</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?= site_url(); ?>">Home <span class="sr-only">(current)</span></a></li>

                    <?php if (session_me()) { ?>
                        <li><a href="<?= site_url(); ?>/ticket-list.php">List Ticket</a></li>
                        <li><a href="<?= site_url(); ?>/open-ticket.php">Open Ticket</a></li>
                    <?php } ?>
                </ul>

                <ul class="nav navbar-nav">

                </ul>

                <?php
                if (!session_me()) {
                    echo '<button type="button" class="btn btn-default navbar-btn navbar-right" data-toggle="modal" data-target="#myModal">Login</button>';
                } else {
                    echo '<a href="' . site_url() . '/logout.php" class="btn btn-default navbar-btn navbar-right">Logout</a>';
                }
                ?>
            </div>
        </div><!-- /.container-fluid -->
    </nav>



    <!-- ISI  -->
    <div class="container">
        <div class="page-header">
            <h1>Ticket List</h1>
        </div>

        <div class="card mb-4 mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover table-striped table-bordered" width="100%" cellspacing="0" style="font-size: 1rem;">
                        <thead>
                            <tr style="font-weight:bold;">
                                <th>NO</th>
                                <th>HELP</th>
                                <th>FULL NAME</th>
                                <th>SUBJECT</th>
                                <th>SERVICE</th>
                                <th>DEPARTMENT</th>
                                <th>PRIORITY</th>
                                <th>MESSAGE</th>
                                <th>STATUS</th>
                                <th>TIME START</th>
                                <th>TIME FINISH</th>
                                <th>DURATION</th>
                                <th>PROBLEM SOLVING</th>
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
                                    <td><?php echo nl2br($val['tt_problem_solving']); ?></td>
                                </tr>
                            </tbody>

                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- FOOTER -->
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <form class="modal-content" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Login</h4>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left:35px;margin-right:35px;">
                        <div class="col-md-12 form-group">
                            <label for="">USERNAME:</label>
                            <input type="text" name="username" class="form-control" value="customer">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="">PASSWORD:</label>
                            <input type="password" name="password" class="form-control" value="123">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="login" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a href="../index.html"><button type="button" class="btn btn-default">Kembali</button></a>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['login'])) {
        $user = Q_mres($_POST['username']);
        $pass = Q_mres(md5($_POST['password']));
        $result = Q_array("SELECT * FROM tbl_user WHERE tu_user='$user' AND tu_pass='$pass' AND tu_role='customer'");
        if (count($result) > 0) {
            $_SESSION['login'] = true;
            $_SESSION['datauser'] = $result[0];
        }
        redirect_to(site_url(true));
    }
    ?>

    <br>
    <br>
    <br>
    <br>

    <?php
    var_dump($_SESSION);
    ?>
    <br>
    <br>
    <br>
    <footer>&copy; PT Elastomix Indonesia | HELPDESK</footer>
</body>

</html>