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
    <title>HELPDESK</title>
    <link rel="shortcut icon" href="images/icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
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
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Data <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?= site_url(); ?>/department.php">Department</a></li>
                                <li><a href="<?= site_url(); ?>/priority.php">Priority</a></li>
                                <li><a href="<?= site_url(); ?>/service.php">Service</a></li>
                                <li><a href="<?= site_url(); ?>/users.php">Users</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= site_url(); ?>/ticket-list.php">List Ticket</a></li>
                        <li><a href="<?= site_url(); ?>/open-ticket.php">Open Ticket</a></li>
                        <li><a href="<?= site_url(); ?>/excel.php">Laporan</a></li>
                        <!-- <li><a href="<?= site_url(); ?>/laporanex.php">export</a></li> -->
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
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>