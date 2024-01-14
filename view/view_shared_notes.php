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

<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Notes Shared as Editor</h2>
        <div class="list-group">
            <!-- Iterate through notes shared as editor -->
            <?php foreach ($sharedAsEditor as $note) : ?>
                <a href="#" class="list-group-item list-group-item-action">
                    <?= htmlspecialchars($note->title) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <h2 class="mb-4 mt-5">Notes Shared as Reader</h2>
        <div class="list-group">
            <!-- Iterate through notes shared as reader -->
            <?php foreach ($sharedAsReader as $note) : ?>
                <a href="#" class="list-group-item list-group-item-action">
                    <?= htmlspecialchars($note->title) ?>
                    </a>
                <?php endforeach; ?>
        </div>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>