<!DOCTYPE html>
<html>

<head>
    <title>Paramètres - Google Keep</title>
    <base href="<?= $web_root ?>">
    <!-- Inclure Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>

<body>

    <div class="container mt-4">
        <h1>Hey <?= $user->getFullName() ?> </h1>
        <ul>
            <li> <a href="">edit profil</a> </li>
            <li> <a href="">change password</a> </li>
            <li> <a href="">Log out</a> </li>
        </ul>
    </div>

    <!-- Scripts Bootstrap (optionnel si nécessaire pour les fonctionnalités JavaScript de Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>

</html>