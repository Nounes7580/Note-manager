<!DOCTYPE html>
<html>
<head>
    <title>Paramètres - Google Keep</title>
    <base href="<?= $web_root ?>">
    <!-- Inclure Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Hey <?=$user->getFullName()?> </h1>
        <ul>
        <li> <a href="">edit profil</a> </li>
        <li> <a href="">change password</a> </li> 
        <li> <a href="">Log out</a> </li>
        </ul>
    </div>

    <!-- Scripts Bootstrap (optionnel si nécessaire pour les fonctionnalités JavaScript de Bootstrap) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
</html>