<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form class="modal-content" method="post">
            <div class="modal-header">
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

<br>
<br>
<br>
<br>

<footer>&copy; PT Elastomix Indonesia | HELPDESK</footer>
</body>

</html>

<?php
if (isset($_POST['login'])) {
    $user = Q_mres($_POST['username']);
    $pass = Q_mres(md5($_POST['password']));
    $result = Q_array("SELECT * FROM tbl_user WHERE tu_user='$user' AND tu_pass='$pass' AND tu_role='customer'");
    if (count($result) > 0) {
        $_SESSION['login'] = true;
        $_SESSION['datauser'] = $result[0];
        echo "<script>alert('Login berhasil!');</script>";
        echo "<script>window.location.href = '" . site_url(true) . "';</script>";
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.');</script>";
    }
}
?>