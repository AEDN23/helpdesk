<?php
require_once 'function.php';
require_once 'func.php';
// include 'header.php';


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


<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    session_start();
    if ($_SESSION['datauser']['tu_role'] !== 'customer') {
        header('Location: index.php');
        exit;
    }

    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HELPDESK</title>
    <link rel="shortcut icon" href="images/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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

        td:nth-child(7),
        td:nth-child(13) {
            width: 100px;
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

<body class="sb-nav-fixed">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="home.php ">HELPDESK</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="ticket.php">List Ticket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="open-ticket.php">Open Ticket</a>
                    </li>
                </ul>
            </div>
            <a href="logout.php"> <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Logout</button></a>
        </div>
    </nav>





    <main>
        <div class="container-fluid px-4">
            <div class="page-header">
                <h1>Ticket List</h1>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-striped table-bordered" width="100%" style="font-size: 1rem;" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>HELP</th>
                                <th>FULL NAME</th>
                                <th>SUBJECT</th>
                                <!-- <th>SERVICE</th> -->
                                <th>DEPARTMENT</th>
                                <!-- <th>PRIORITY</th> -->
                                <th>STATUS</th>
                                <!-- <th>TIME START</th>
                                <th>TIME FINISH</th> -->


                                <th>DURATION</th>
                                <th style="min-width: 300px;">MESSAGE</th>
                                <th style="min-width: 300px;">PROBLEM SOLVING</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><?php echo $val['tt_no_id']; ?></td>
                                    <td><?php echo $val['tu_full_name']; ?> <br> <?php echo $val['tu_email']; ?></td>
                                    <td><?php echo $val['tt_subject']; ?></td>
                                    <td><?php echo $val['td_name']; ?></td>
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

                                    <td><?php echo $val['tt_duration']; ?></td>
                                    <td><?php echo $val['tt_message']; ?></td>
                                    <td><?php echo ($val['tt_problem_solving']); ?></td>
                                <?php } ?>
                                </tr>
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </main>

    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>