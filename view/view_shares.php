<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shared Notes</title>
    <!-- Bootstrap CSS and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .bi-arrow-left {
        font-size: 30px;
        position: fixed;
        top: 20px;
        left: 40px;
        /* Déplacer légèrement vers la droite */
        z-index: 1000;
        color: white;

    }
</style>

<body>
    <div>
        <a href="javascript:history.back()" class="bi bi-arrow-left"></a>
    </div>



    <div class="container mt-5">

        <?php if (!empty($usersToShareWith)) : ?>
            <h2>Shares:</h2>
            <div class="row align-items-center">
                <!-- Sélecteur d'utilisateur -->
                <div class="col">
                    <select class="form-select" id="userSelect" name="user_id">
                        <option value="">-User-</option>
                        <?php foreach ($usersToShareWith as $user) : ?>
                            <option value="<?= htmlspecialchars($user->id) ?>"><?= htmlspecialchars($user->full_name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sélecteur de permission -->
                <div class="col">
                    <select class="form-select" id="permissionSelect" name="permission">
                        <option value="editor">Editor</option>
                        <option value="reader">Reader</option>
                    </select>
                </div>

                <!-- Bouton de partage -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">+</button>
                </div>
            </div>
        <?php else : ?>
            <p class="text-muted">This is not shared yet</p>
        <?php endif; ?>
    </div>


    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>