<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <title>Paramètres - Google Keep</title>
    <base href="<?= $web_root ?>">
    <!-- Inclure Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        .header {
            margin-bottom: 40px;
        }
        .list-group-item {
            background-color: transparent;
            border: none;
        }
        .list-group-item:hover {
            background-color: #343a40;
        }
        .list-group-item a {
            color: #f8f9fa;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="header text-center">
            <h1 class="text-white mb-3">Welcome, <?= $user->getFullName() ?></h1>
            <p class="text-muted">Manage your account settings</p>
        </div>

        <div class="list-group">
            <a href="" class="list-group-item list-group-item-action">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
            <a href="<?php echo $web_root; ?>Main/change_password" class="list-group-item list-group-item-action">
                <i class="bi bi-key-fill"></i> Change Password
            </a>
            <a href="" class="list-group-item list-group-item-action">
                <i class="bi bi-box-arrow-right"></i> Log Out
            </a>
        </div>
    </div>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>