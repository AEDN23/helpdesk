<?php
require_once("func.php")
?>

<!-- USER.PHP START -->
<?php
if (isset($_POST['save-user'])) {
    $a = Q_mres('customer');
    $d = Q_mres($_POST['fullname']);
    $e = Q_mres($_POST['email']);
    $user = Q_mres($_POST['username']);
    // $pass = Q_mres($_POST['password']);
    $pass = 'Default_pass';
    $user = 'Default_user';

    $hashed_password = md5($pass);

    $sql = "INSERT INTO tbl_user (tu_role, tu_user, tu_pass, tu_full_name, tu_email) 
        VALUES ('$a', '$user', '$hashed_password', '$d', '$e')";

    if (Q_execute($sql)) {
        redirect_to("users.php");
    }
}

if (isset($_POST['update-user'])) {
    $a = Q_mres('customer');
    $d = Q_mres($_POST['fullname']);
    $e = Q_mres($_POST['email']);
    $f = Q_mres($_POST['id']);

    $sql = "UPDATE tbl_user SET tu_role='$a', tu_full_name='$d', tu_email='$e' WHERE tu_id='$f'";
    if (Q_execute($sql)) {
        redirect_to("users.php");
    }
}

if (isset($_POST['delete-user'])) {
    $a = Q_mres($_POST['id']);

    $sql = "DELETE FROM tbl_user WHERE tu_id='$a'";
    if (Q_execute($sql)) {
        redirect_to("users.php");
    }
}
?>
<!-- USER.PHP END -->

<!-- TICKET-LIST.PHP START -->
<?php
if (isset($_POST['update-tl'])) {
    $a = Q_mres($_POST['status']); // Status baru
    $b = Q_mres($_POST['id']); // ID tiket
    $c = Q_mres($_POST['problem-solving']); // Problem solving

    // Waktu Jakarta (WIB)
    date_default_timezone_set('Asia/Jakarta');
    $updatedTime = date('Y-m-d H:i:s'); // Waktu sekarang WIB

    // Query untuk mendapatkan waktu tt_created
    $query = "SELECT tt_created FROM tbl_ticket WHERE tt_id='$b'";
    $result = Q_row($query);
    $createdTime = $result['tt_created'];

    // Hitung durasi (dalam menit)
    $duration = null;
    if ($createdTime) {
        // Menghitung selisih waktu antara tt_created dan tt_updated
        $durationInSeconds = strtotime($updatedTime) - strtotime($createdTime);
        $durationInMinutes = round($durationInSeconds / 60); // Hasil dalam menit

        // Konversi durasi sesuai dengan lebih dari 60 menit atau 24 jam
        if ($durationInMinutes >= 1440) { // 1440 menit = 24 jam
            $days = floor($durationInMinutes / 1440);
            $hours = floor(($durationInMinutes % 1440) / 60);
            $duration = $days . ' hari ' . $hours . ' jam';
        } elseif ($durationInMinutes >= 60) { // 60 menit = 1 jam
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;
            $duration = $hours . ' jam ' . $minutes . ' menit';
        } else {
            $duration = $durationInMinutes . ' menit';
        }
    }

    // Update database dengan tt_updated dan tt_duration
    $sql = "UPDATE tbl_ticket SET 
                tt_status='$a', 
                tt_problem_solving='$c', 
                tt_updated='$updatedTime', 
                tt_duration='$duration' 
            WHERE tt_id='$b'";

    if (Q_execute($sql)) {
        redirect_to("ticket-list.php");
    }
}





?>
<!-- TICKET-LIST.PHP END -->








<!-- SERVICE.PHP START -->
<?php
if (isset($_POST['save-service'])) {
    $a = Q_mres($_POST['name']);
    $b = Q_mres($_POST['description']);

    $sql = "INSERT INTO tbl_service (ts_name, ts_description) VALUES ('$a', '$b')";
    if (Q_execute($sql)) {
        redirect_to("service.php");
    }
}

if (isset($_POST['update-service'])) {
    $a = Q_mres($_POST['name']);
    $b = Q_mres($_POST['description']);
    $c = Q_mres($_POST['id']);

    $sql = "UPDATE tbl_service SET ts_name='$a', ts_description='$b' WHERE ts_id='$c'";
    if (Q_execute($sql)) {
        redirect_to("service.php");
    }
}

if (isset($_POST['delete-service'])) {
    $a = Q_mres($_POST['id']);

    $sql = "DELETE FROM tbl_service WHERE ts_id='$a'";
    if (Q_execute($sql)) {
        redirect_to("service.php");
    }
}
?>
<!-- SERVICE.PHP END -->

<!-- PRIORITY.PHP START -->

<?php
if (isset($_POST['save-priority'])) {
    $a = Q_mres($_POST['name']);

    $sql = "INSERT INTO tbl_priority (tp_name) VALUES ('$a')";
    if (Q_execute($sql)) {
        redirect_to("priority.php");
    }
}

if (isset($_POST['update-priority'])) {
    $a = Q_mres($_POST['name']);
    $c = Q_mres($_POST['id']);

    $sql = "UPDATE tbl_priority SET tp_name='$a' WHERE tp_id='$c'";
    if (Q_execute($sql)) {
        redirect_to("priority.php");
    }
}

if (isset($_POST['delete-priority'])) {
    $a = Q_mres($_POST['id']);

    $sql = "DELETE FROM tbl_priority WHERE tp_id='$a'";
    if (Q_execute($sql)) {
        redirect_to("priority.php");
    }
}
?>
<!-- PRIORITY.PHP END -->

<!-- DEPARTEMENT.PHP START -->

<?php
if (isset($_POST['save-dept'])) {
    $a = Q_mres($_POST['name']);
    $b = Q_mres($_POST['description']);

    $sql = "INSERT INTO tbl_department (td_name, td_description) VALUES ('$a', '$b')";
    if (Q_execute($sql)) {
        redirect_to("department.php");
    }
}

if (isset($_POST['update-dept'])) {
    $a = Q_mres($_POST['name']);
    $b = Q_mres($_POST['description']);
    $c = Q_mres($_POST['id']);

    $sql = "UPDATE tbl_department SET td_name='$a', td_description='$b' WHERE td_id='$c'";
    if (Q_execute($sql)) {
        redirect_to("department.php");
    }
}

if (isset($_POST['delete-dept'])) {
    $a = Q_mres($_POST['id']);

    $sql = "DELETE FROM tbl_department WHERE td_id='$a'";
    if (Q_execute($sql)) {
        redirect_to("department.php");
    }
}
?>
<!-- DEPARTEMENT.PHP END -->

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