<?php include('header.php'); ?>

<?php
$user = Q_array("SELECT * FROM tbl_user WHERE tu_role='customer' ORDER BY tu_id DESC");
$department = Q_array("SELECT * FROM tbl_department ORDER BY td_id DESC");
$service = Q_array("SELECT * FROM tbl_service ORDER BY ts_id DESC");
$priority = Q_array("SELECT * FROM tbl_priority ORDER BY tp_id DESC");
?>

<div class="container">
    <div class="page-header">
        <h1>New Ticket</h1>
    </div>

    <!-- UBAH FORM MENJADI MULTIPART/FORM-DATA -->
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="">User:</label>
                <select name="user" class="form-control" required>
                    <option value="">~ select user ~</option>
                    <?php foreach ($user as $key => $u) {
                        echo '<option value="' . $u['tu_id'] . '">' . $u['tu_full_name'] . ' - ' . $u['tu_email'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-md-8 form-group">
                <label for="">Subject:</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="col-md-4 form-group">
                <label for="">Department:</label>
                <select name="department" class="form-control" required>
                    <option value="">~ select department ~</option>
                    <?php foreach ($department as $key => $u) {
                        echo '<option value="' . $u['td_id'] . '">' . $u['td_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="">Service:</label>
                <select name="service" class="form-control" required>
                    <option value="">~ select service ~</option>
                    <?php foreach ($service as $key => $u) {
                        echo '<option value="' . $u['ts_id'] . '">' . $u['ts_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="">Priority:</label>
                <select name="priority" class="form-control" required>
                    <option value="">~ select priority ~</option>
                    <?php foreach ($priority as $key => $u) {
                        echo '<option value="' . $u['tp_id'] . '">' . $u['tp_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <div class="col-md-12 form-group">
                <label for="">Message:</label>
                <textarea name="message" class="form-control" rows="10" required></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="foto_before">Foto Before (Max 2MB):</label>
            <input type="file" name="foto_before" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
        </div>

        <button type="submit" name="save" class="btn btn-primary btn-lg">Submit Ticket</button>
    </form>
</div>

<?php
if (isset($_POST['save'])) {
    $a = Q_mres($_POST['user']);
    $b = Q_mres($_POST['subject']);
    $c = Q_mres($_POST['department']); // <--- Variabel department
    $d = Q_mres($_POST['service']);
    $e = Q_mres($_POST['priority']);
    $f = Q_mres($_POST['message']);

    // Handle file upload untuk foto before
    $foto_before = '';

    if (isset($_FILES['foto_before']) && $_FILES['foto_before']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/before/';

        // Buat folder uploads jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES['foto_before']['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validasi ekstensi file
        if (in_array(strtolower($fileExtension), $allowedExtensions)) {
            // Validasi ukuran file (max 2MB)
            if ($_FILES['foto_before']['size'] <= 2 * 1024 * 1024) {

                // --- AWAL MODIFIKASI NAMA FILE ---

                // Bersihkan nama departemen untuk digunakan dalam nama file (misalnya ganti spasi jadi underscore)
                $departemen_safe = str_replace(' ', '_', strtolower($c));

                // Ambil tanggal dengan format d-m-Y
                $tanggal_sekarang = date('d-m-Y');

                // Generate unique filename sesuai format baru
                // Format: before_trouble_[nama_departemen]_[tanggal-bulan-tahun].[ekstensi]
                // Tambahkan waktu (time()) untuk memastikan nama file unik, jika diperlukan.
                $filename = 'before_trouble_' . $departemen_safe . '_' . $tanggal_sekarang . '_' . time() . '.' . $fileExtension;

                // Pastikan ada separator folder jika belum ada di $uploadDir
                $uploadPath = $uploadDir . $filename;

                // --- AKHIR MODIFIKASI NAMA FILE ---

                // Pindahkan file ke folder uploads
                if (move_uploaded_file($_FILES['foto_before']['tmp_name'], $uploadPath)) {
                    $foto_before = $uploadPath;
                    echo "<script>alert('Foto berhasil diupload!');</script>";
                } else {
                    echo "<script>alert('Gagal mengupload foto!');</script>";
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.');</script>";
            }
        } else {
            echo "<script>alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF.');</script>";
        }
    }

    // Generate tt_no_id otomatis
    $query = "SELECT COUNT(*) AS total_rows FROM tbl_ticket";
    $result = Q_row($query);
    $totalRows = $result['total_rows'];
    $nextId = $totalRows + 1;
    $tt_no_id = "ITHDK" . "-" . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    // Insert ke database dengan foto before
    $sql = "INSERT INTO tbl_ticket (tt_no_id, tt_user, tt_subject, tt_department, tt_service, tt_priority, tt_message, tt_foto_before) 
             VALUES ('$tt_no_id', '$a', '$b', '$c', '$d', '$e', '$f', '$foto_before')";

    if (Q_execute($sql)) {
        echo "<script>alert('Ticket berhasil dibuat!');</script>";
        redirect_to("ticket-list.php");
    } else {
        echo "<script>alert('Gagal menyimpan ticket!');</script>";
    }
}
?>



<?php include('footer.php'); ?>