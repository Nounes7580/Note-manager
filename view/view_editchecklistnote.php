<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer Note Checklist</title>
    <!-- Reprise du même lien Bootstrap CSS utilisé dans les autres pages -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Inclure d'autres fichiers CSS si nécessaire pour correspondre au style des autres pages -->
</head>
<body>
    <div class="container mt-4">
        <h2>Éditer Note Checklist</h2>
        <form action="chemin_vers_le_script_de_traitement" method="post">
            <input type="hidden" name="id" value="<?php echo $note->id; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($note->title); ?>" required>
            </div>

            <?php foreach ($note->getItems() as $index => $item): ?>
                <div class="mb-3">
                    <label for="item<?php echo $index; ?>" class="form-label">Élément <?php echo $index + 1; ?></label>
                    <input type="text" class="form-control" id="item<?php echo $index; ?>" name="items[]" value="<?php echo htmlspecialchars($item->content); ?>">
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <button type="button" class="btn btn-secondary">Ajouter un élément</button>
            </div>

            <button type="submit" class="btn btn-primary">Sauvegarder les modifications</button>
        </form>
    </div>

    <!-- Inclure Bootstrap Bundle JS, identique aux autres pages -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Inclure tout autre script JavaScript nécessaire -->
</body>
</html>
