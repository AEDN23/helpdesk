<?php
session_start();
require_once("func.php");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HELPDESK</title>
    <link rel="shortcut icon" href="images/icon.png">
    <link href="css/styles.css" rel="stylesheet" />
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


                    <?php if (session_me()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="ticket.php">List Ticket</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="open-ticket.php">Open Ticket</a>
                        </li>
                    <?php } ?>
                     
                </ul>

            </div>
            <a href="logout.php"> <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Logout</button></a>
        </div>
    </nav>