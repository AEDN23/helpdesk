<?php
require_once("func.php");
?>
<html>

<head>
    <title style="text-align: center;">LAPORAN DATA HELPDESK</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
    <!-- <style>
        td:nth-child(7),
        td:nth-child(12) {
            width: 1000px;
        }
    </style> -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            box-sizing: border-box;
            font-size: 0.8rem;
            /* Smaller font size */
        }

        table th,
        table td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 6px;
            /* Smaller padding */
            font-size: 0.8rem;
            /* Smaller font size */
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: auto;
            margin: 0mm;
        }

        /* Styling khusus untuk title saat dicetak */
        title {
            display: block;
            text-align: center;
            position: running(header);
        }

        @page {
            @top-center {
                content: element(header);
            }
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

        #print-area {
            display: block;
        }

        /* ol {
            display: none;
        } */
    </style>

</head>

<body>
    <div class="container" id="print-area">
        <h2 class="text-center">Data HELPDESK</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Halaman awal</a></li>
            <li class="breadcrumb-item active">Import Dokumen</li>
        </ol>

        <div class="data-tables datatable-dark">

            <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
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
                            <!-- <td><//?php echo $key + 1; ?></td> -->
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
                        <?php } ?>
                        </tr>
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
                        title: 'List Barang'
                    },
                    {
                        extend: 'pdf',
                        title: 'List Barang',
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