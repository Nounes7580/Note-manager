<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Éditer Note Checklist</title>
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

    .container {
        color: white; /* Couleur du texte */
    }

    .btn-delete {
        background-color: #dc3545; /* Couleur de fond pour les boutons de suppression */
        color: white;
    }

    .btn-add {
        background-color: #0d6efd; /* Couleur de fond pour le bouton d'ajout */
        color: white;
    }

    .input-group .form-control {
        
        color: white; /* Couleur du texte pour les champs de saisie */
        border-color: #495057;
    }

    .input-group-text {
        background-color: transparent;
        border: none;
    }
 
    .input-group + .input-group {
        margin-top: 10px; /* Ajoute de l'espace entre les groupes d'input */
    }
    .deleted-item {
        text-decoration: line-through;
        color: grey;
    }
</style>
<body>
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="javascript:history.back()">
            <i class="bi bi-arrow-left"></i>
        </a>
        <a onclick="document.getElementById('checklisteditForm').submit(); return false;" style="cursor: pointer;">
            <i class="bi bi-floppy2-fill"></i>
        </a>
    </div>
</nav>
<div class="container mt-4">
    <form id="checklisteditForm" action="chemin_vers_le_script_de_traitement" method="post">
        <input type="hidden" name="id" value="<?php echo $note->id; ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($note->title); ?>" required>
        </div>
        <label for="item">Items</label>
            <?php foreach ($note->getItems() as $index => $item): ?>
                <div class="input-group mb-3">
                    <div class="input-group-text bg-secondary">
                        <i class="bi <?php echo $item->checked ? 'bi-check-circle-fill checked-icon' : 'bi-circle unchecked-icon'; ?>"></i>
                    </div>
                    <input type="text" class="form-control" name="items[]" value="<?php echo htmlspecialchars($item->content); ?>" readonly>
                    
                    <input type="hidden" name="checked[]" value="<?php echo $item->checked ? '1' : '0'; ?>">
                    <button class="btn btn-delete " type="button"><i class="bi bi-dash-lg"></i></button>
                </div>
            <?php endforeach; ?>
        
 <label for="newItem">New Item</label> 
        <div class="input-group mb-3">
          
            <input type="text" class="form-control" name="new_item">
            <button type="button" class="btn btn-add"><i class="bi bi-plus-lg"></i></button>
        </div>

       
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
