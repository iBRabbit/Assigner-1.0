<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark ">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Assigner</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <a class="nav-link" href = "<?= BASE_URL . "notifications/notifications_header.php" ?>">
                        <button type="button" class="btn btn-primary position-relative">
                            <i class="bi bi-bell-fill"></i>
                            <!-- Badge -->
                            <?php if($unopenedNotifsSize > 0) :?>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $unopenedNotifsSize ?>
                                <span class="visually-hidden">unread messages</span>
                            </span>
                            <?php endif; ?>
                            <!-- Badge -->
                        </button>
                    </a>

                    <li class="nav-item">
                        <a class="nav-link <?= ActiveLocation("index.php");?>" aria-current="page" href="<?= BASE_URL . "index.php" ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ActiveLocation("group");?>" href = "<?= BASE_URL . "group/mygroup.php" ?>" >Groups</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ActiveLocation("inbox");?>" href="#"> Inbox</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?= GetUserFullName($accountID) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">

                            <li><a class="dropdown-item" href="#">My Profile</a></li>
                            <li>
                                <a class="dropdown-item " href="#">
                                    <form action="<?= BASE_URL . "logout.php"?>" class="margin-right:5rem" ethod=" post">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                name="logout">Logout</button>
                                        </div>
                                    </form>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- End of Navbar -->