<script>
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "
    SELECT * FROM tbl_ticket a 
    LEFT JOIN tbl_user b ON b.tu_id=a.tt_user
    LEFT JOIN tbl_department c ON c.td_id=a.tt_department
    LEFT JOIN tbl_service d ON d.ts_id=a.tt_service
    LEFT JOIN tbl_priority e ON e.tp_id=a.tt_priority
    WHERE tt_status!='DELETE' AND (
        tt_subject LIKE '%$search%' OR
        tu_full_name LIKE '%$search%' OR
        td_name LIKE '%$search%'
    )
    ORDER BY tt_id DESC
";
</script>
