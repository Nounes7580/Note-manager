<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];
?>
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
        color: white; /* Icônes blanches */
        font-size: 1.2rem; /* Taille de l'icône */
    }

    .nav-link:not(:last-child) {
        margin-right: 1rem; /* Espacement entre les boutons */
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-valid {
        border-color: #28a745;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
    }

    .input-group-text {
        background: transparent;
        border: none;
    }

    /* Ajoutez cette règle pour cacher les icônes par défaut */
    .input-group-text .bi {
        display: none;
    }

    /* Affichez l'icône seulement si le champ est validé ou invalidé après la soumission */
    .is-valid + .input-group-append .bi, .is-invalid + .input-group-append .bi {
        display: inline-block;
    }
</style>
<body>
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <!-- Bouton de retour aligné à gauche -->
        <a class="navbar-brand" href="javascript:history.back()">
            <i class="bi bi-arrow-left"></i> <!-- Flèche pointant vers la gauche -->
        </a>
    </div>
</nav>

<!-- Formulaire pour ajouter une note checklist -->
<div class="container mt-5">
    <form action="./add_checklistnote" method="post">
        <!-- Titre de la checklist -->
        <div class="mb-3">
            <label for="titleInput" class="form-label">Titre</label>
            <?php $title = $title ?? ''; ?>
            <input type="text" class="form-control <?php echo empty($errors['title']) ? 'is-valid' : (!empty($errors['title']) ? 'is-invalid' : ''); ?>"
                   id="titleInput" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <?php if (!empty($errors['title'])): ?>
                <div class="invalid-feedback">
                    <?php echo htmlspecialchars($errors['title']); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($validFields['title'])): ?>
                <span class="valid-feedback"><i class="bi bi-check-circle-fill text-success"></i></span>
            <?php endif; ?>
        </div>

        <!-- Champs pour les éléments de la checklist -->
        <label class="form-label">Éléments</label>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <?php
            // Assurez-vous que la clé du tableau existe dans $validFields
            $validKey = "item$i";
            if (!array_key_exists($validKey, $validFields)) {
                $validFields[$validKey] = false; // Initialisez la clé à false si elle n'existe pas
            }
            ?>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">.</span>
                <input type="text" name="item<?= $i ?>" class="form-control <?php if (!empty($errors["item$i"]) || !$validFields[$validKey]) echo 'is-invalid'; elseif ($validFields[$validKey]) echo 'is-valid'; ?>"
                       value="<?php echo htmlspecialchars($items[$i - 1] ?? ''); ?>">
                <?php if ($validFields[$validKey]): ?>
                    <!-- Votre code pour afficher l'icône de validation -->
                <?php endif; ?>
                <?php if (!empty($errors["item$i"])): ?>
                    <div class="invalid-feedback">
                        <?php echo htmlspecialchars($errors["item$i"]); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($validFields[$validKey])): ?>
                    <div class="valid-feedback"><i class="bi bi-check-circle-fill text-success"></i></div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>

        <button type="submit" class="btn btn-primary">Créer la note</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>