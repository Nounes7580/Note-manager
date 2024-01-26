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
    <div class="container mt-5">
        <h2>Shares:</h2>
        <?php if (!empty($shares)) : ?>
            <?php foreach ($shares as $share) : ?>
                <div class="mb-2">
                    <span><?= htmlspecialchars($share['full_name']) ?></span>
                    <span class="badge bg-secondary"><?= htmlspecialchars($share['permission']) ?></span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-muted">This note is not shared yet.</p>
        <?php endif; ?>
        <!-- Sélecteur d'utilisateur et de permission avec bouton d'ajout -->
        <div class="d-flex justify-content-start align-items-center mt-4">
            <select class="form-select" id="userSelect" name="user_id" style="margin-right: -1px;">
                <option value="">-User-</option>
                <?php foreach ($usersToShareWith as $user) : ?>
                    <option value="<?= htmlspecialchars($user->id) ?>"><?= htmlspecialchars($user->full_name) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Sélecteur de permission -->
            <select class="form-select" id="permissionSelect" name="permission" style="margin-right: -1px; margin-left: -1px;">
                <option value="editor">Editor</option>
                <option value="reader">Reader</option>
            </select>

            <!-- Bouton de partage -->
            <button type="submit" class="btn btn-primary" style="margin-left: -1px;">+</button>
        </div>

    </div>


    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>