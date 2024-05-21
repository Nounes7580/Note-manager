<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Labels</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $web_root; ?>notes/">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </nav>

    <div class="container mt-3">
        <h1>Gestion des labels</h1>
        <form method="post" action="<?php echo $web_root; ?>notes/addLabel">
            <div class="mb-3">
                <label for="labelInput" class="form-label">Ajouter un nouveau label</label>
                <input type="text" class="form-control" id="labelInput" name="label" placeholder="Enter label" list="labelSuggestions">
                <datalist id="labelSuggestions">
                    <?php foreach ($availableLabels as $availableLabel) : ?>
                        <option value="<?= htmlspecialchars($availableLabel) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <input type="hidden" name="noteId" value="<?= htmlspecialchars($noteId) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>

        <h2>Labels existants</h2>
        <ul class="list-group">
            <?php if (empty($labels)): ?>
                <li class="list-group-item">No labels found.</li>
            <?php else: ?>
                <?php foreach ($labels as $label) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($label) ?>
                        <form method="post" action="<?php echo $web_root; ?>notes/deleteLabel">
                            <input type="hidden" name="noteId" value="<?= htmlspecialchars($noteId) ?>">
                            <input type="hidden" name="label" value="<?= htmlspecialchars($label) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
