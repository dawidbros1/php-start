<li class="nav-item">
    <a class="nav-link" href="#">Item 1</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="#">Item 2</a>
</li>

<?php if ($user): ?>

<div class="collapse navbar-collapse position-absolute" id="username">

<div class="dropdown">
    <ul class="navbar-nav">
        <!-- ONLY ADMIN -->

        <?php if ($this->user->isAdmin()): ?>
            <li class="nav-item dropdown">
                <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Zarządzaj
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?=$route['social.list']?>">Media społecznościowe</a></li>
                </ul>
            </li>
        <?php endif;?>

        <!-- USERS -->
        <li class="nav-item dropdown">
            <a class="nav-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?=$user->username?>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                <li><a class="dropdown-item" href="<?=$route['user.profile']?>">Profil</a></li>
                <li><a class="dropdown-item" href="<?=$route['user.logout']?>">Wyloguj</a></li>
            </ul>
        </li>
    </ul>
</div>

<?php endif;?>