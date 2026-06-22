<?php
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$initial = strtoupper(substr($username, 0, 1));
?>
<!-- Topbar Component -->
<header class="topbar">
    <div class="search-box">
        <i class="bi bi-search text-muted"></i>
        <input type="text" placeholder="Search book...">
    </div>

    <div class="topbar-right">
        <button class="btn-live">Live</button>
        <a href="#" class="icon-btn"><i class="bi bi-moon"></i></a>
        <a href="#" class="icon-btn"><i class="bi bi-bell"></i></a>
        
        <div class="dropdown">
            <div class="user-profile dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                <?= $initial ?>
            </div>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                <li><h6 class="dropdown-header">Halo, <?= htmlspecialchars($username) ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>
