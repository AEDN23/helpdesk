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
                <li class="active"><a href="<?= site_url(); ?>/home.php">Home <span class="sr-only">(current)</span></a></li>

                <?php if (session_me()) { ?>
                    <li><a a href="<?= site_url(); ?>/ticket.php">List Ticket</a></li>
                    <li><a href="<?= site_url(); ?>/open-ticket.php">Open Ticket</a></li>
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