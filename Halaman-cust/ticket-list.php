<?php
require_once("func.php");
include "header.php";
?>

<style>
    td:nth-child(8),
    td:nth-child(13) {
        width: 1000px;
    }

    .foto-thumbnail {
        max-width: 80px;
        max-height: 80px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 2px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .foto-thumbnail:hover {
        transform: scale(2);
        z-index: 1000;
        position: relative;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    /* PERBAIKAN: Style untuk status - pastikan selector tepat */
    .status-new {
        background-color: #fff3cd !important;
        color: #856404 !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    .status-process {
        background-color: #cce7ff !important;
        color: #004085 !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    .status-pending {
        background-color: #ffeaa7 !important;
        color: #2d3436 !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    .status-cancel {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    .status-done {
        background-color: #d4edda !important;
        color: #155724 !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    .status-default {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 4px;
    }

    /* Tambahan untuk memastikan style diterapkan pada td */
    td[class*="status-"] {
        text-align: center !important;
        vertical-align: middle !important;
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
                            <!-- HAPUS ACTION KOLOM UNTUK CUSTOMER -->
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
                                <!-- PERBAIKAN: Pastikan class diterapkan dengan benar -->
                                <td class="<?php echo $statusClass; ?>" style="text-align: center; vertical-align: middle;">
                                    <?php echo htmlspecialchars($val['tt_status']); ?>
                                </td>
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
                                <td><?php echo ($val['tt_problem_solving']); ?></td>

                                <!-- KOLOM BEFORE - TAMPILKAN FOTO BEFORE -->
                                <td style="text-align: center;">
                                    <?php
                                    if (!empty($val['tt_foto_before'])) {
                                        echo '<a href="' . $val['tt_foto_before'] . '" target="_blank" title="Klik untuk lihat ukuran penuh">';
                                        echo '<img src="' . $val['tt_foto_before'] . '" alt="Foto Before" class="foto-thumbnail">';
                                        echo '</a>';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>

                                <!-- KOLOM AFTER -->
                                <td style="text-align: center;">
                                    <?php
                                    if (!empty($val['tt_foto_after'])) {
                                        echo '<a href="' . $val['tt_foto_after'] . '" target="_blank" title="Klik untuk lihat ukuran penuh">';
                                        echo '<img src="' . $val['tt_foto_after'] . '" alt="Foto After" class="foto-thumbnail">';
                                        echo '</a>';
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>

                                <!-- HAPUS TOMBOL ACTION UNTUK CUSTOMER -->
                                <!-- <td>
                                    <a class='btn btn-default' data-toggle='modal' data-target='#ed-<?php echo $key; ?>'>Option</a>
                                </td> -->
                            </tr>
                        </tbody>
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