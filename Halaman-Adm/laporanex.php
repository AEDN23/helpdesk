<?php
require_once("func.php");
// Proses selanjutnya jika sudah login

// Date collections for filters
$current_month_start = date('Y-m-01');
$current_month_end = date('Y-m-t');



$k_start = isset($_GET['start']) ? $_GET['start'] : $current_month_start;
$k_end = isset($_GET['end']) ? $_GET['end'] : $current_month_end;

?>
<html>

<head>
    <title>Export Barang Keluar</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
    <style>


        

        th,
        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">HELPDESK</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <a href="?start=<?= $current_month_start; ?>&end=<?= $current_month_end; ?>" class="btn btn-default">Bulan Ini</a>

        <h3>Filter Harian</h3>
        <form method="GET">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="">Start Date:</label>
                    <input type="date" name="start" class="form-control" onclick="this.showPicker()">
                </div>
                <div class="col-md-3 form-group">
                    <label for="">End Date:</label>
                    <input type="date" name="end" class="form-control" onclick="this.showPicker()" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3 form-group">
                    <input type="submit" name="filter" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>

        <p>Data HELPDESK dari tanggal <b><?= date('d F Y', strtotime($k_start)); ?></b> sampai <b><?= date('d F Y', strtotime($k_end)); ?></b>.</p>

        <div class="data-tables datatable-dark" id="cont">
            <table class="table table-hover table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
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
                                if (!empty($val['tt_updated'])) {
                                    $timestamp = strtotime($val['tt_updated']);
                                    if ($timestamp !== false) {
                                        echo date("d F Y", $timestamp) . "<br>";
                                        echo date("H:i:s A", $timestamp);
                                    } else {
                                        echo "Format tanggal tidak valid.";
                                    }
                                } else {
                                    echo "-";
                                }
                                ?></td>
                            <td><?php echo $val['tt_duration']; ?></td>
                            <td><?php echo nl2br($val['tt_problem_solving']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        title: 'HELPDESK',
                    },
                    {
                        extend: 'pdf',
                        title: 'HELPDESK',
                        customize: function(doc) {
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.alignment = 'center';
                                });
                            });
                            doc.pageOrientation = 'landscape';
                            doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*'];
                            doc.content[1].layout = {
                                hLineWidth: function(i) {
                                    return 0.5;
                                },
                                vLineWidth: function(i) {
                                    return 0.5;
                                },
                                hLineColor: function(i) {
                                    return '#000';
                                },
                                vLineColor: function(i) {
                                    return '#000';
                                },
                                fillColor: function(i) {
                                    return (i === 0 || i === doc.content[1].table.body.length) ? '#ccc' : null;
                                }
                            };
                            // Tambahkan pengaturan agar tabel berada di tengah halaman
                            doc.content[1].margin = [0, 0, 0, 0]; // Hapus margin bawaan tabel
                            doc.content[1].alignment = 'center'; // Rata tengah seluruh tabel
                        }
                    },
                    'print'
                ]
            });
        });
    </script>

    <script src="../../js/jquery-3.5.1.js"></script>
    <script src="../../js/jquery.dataTables.min.js"></script>
    <script src="../../js/dataTables.buttons.min.js"></script>
    <script src="../../js/buttons.flash.min.js"></script>
    <script src="../../js/jszip.min.js"></script>
    <script src="../../js/pdfmake.min.js"></script>
    <script src="../../js/vfs_fonts.js"></script>
    <script src="../../js/buttons.html5.min.js"></script>
    <script src="../../js/buttons.print.min.js"></script>


</body>

</html>