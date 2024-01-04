<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter une Note Checklist</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
  .navbar-custom {
    padding: 1px;
  }
  .navbar-custom .bi {
    color: white; /* White icons */
    font-size: 1.2rem; /* Icon size */
  }
  /* Add custom spacing if needed */
  .nav-link:not(:last-child) {
    margin-right: 1rem; /* Spacing between buttons */
  }
</style>
<body>
<nav class="navbar navbar-expand navbar-custom">
  <div class="container-fluid">
    <!-- Left aligned return button -->
    <a class="navbar-brand" href="javascript:history.back()">
  <i class="bi bi-arrow-left"></i> <!-- Left-pointing arrow -->
</a>
    </div>

</nav>
    <!-- Formulaire pour ajouter une note checklist -->
    <div class="container mt-5">
        <form action="./add_checklistnote" method="post">
            <!-- Titre de la checklist -->
            <div class="mb-3">
                <label for="titleInput" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titleInput" name="title" required>
            </div>

            <!-- Champs pour les éléments de la checklist -->
            <label class="form-label">Éléments</label>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="input-group mb-3">
                    <span class="input-group-text" style="list-style-type: disc;"><?= "." ?></span>
                    <input type="text" class="form-control" name="item<?= $i ?>">
                </div>
            <?php endfor; ?>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Créer la note</button>
        </form>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>