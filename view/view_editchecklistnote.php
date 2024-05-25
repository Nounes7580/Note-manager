<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];

?>
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
        color: #aaa;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .is-invalid + .input-group-append .bi {
        display: inline-block;
    }
</style>
<body>
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
    <button class="navbar-brand btn btn-link" id="backButton" style="color: inherit; text-decoration: none;">
    <i class="bi bi-arrow-left"></i>
</button>

        <a onclick="document.getElementById('checklisteditForm').submit(); return false;" style="cursor: pointer;">
            <i class="bi bi-floppy2-fill"></i>
        </a>
    </div>
</nav>
<div class="container mt-4">
    <form id="checklisteditForm" action="./../save_edited_checklistnote" method="post">
        <input type="hidden" name="id" value="<?php echo $note->id; ?>">
        <?php if (isset($note)): ?>
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control <?php echo ((!$formSubmitted && !empty($errors['title'])) ? 'is-invalid' :'' ); ?>" id="title" name="title" value="<?php echo htmlspecialchars($note->title); ?>" require >
            <?php if (!empty($errors) && isset($errors['title'])): ?>
                <div class="invalid-feedback" >
                    <?php echo htmlspecialchars($errors['title']); ?>
                </div>
            <?php endif; ?>
        </div>
        </form> 
        <?php foreach ($note->getItems() as $index => $item): ?>
<form action="./../delete_checklist_item" method="post" class="mb-3">
    <div class="input-group">
        <div class="input-group-text bg-secondary">
            <i class="bi <?php echo $item->checked ? 'bi-check-circle-fill' : 'bi-circle'; ?>"></i>
        </div>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($item->content); ?>" readonly>
        <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
        <button class="btn btn-delete" type="submit"><i class="bi bi-dash-lg"></i></button>
    </div>
    
</form>
<?php endforeach; ?>
<?php endif; ?>
<!-- Nouveaux éléments -->
<?php if (isset($_SESSION['checklist_items'][$note->id])): ?>
        <?php foreach ($_SESSION['checklist_items'][$note->id] as $tempItemId => $item): ?>
            <form action="./../delete_temporary_item" method="post" class="mb-3">
                <div class="input-group">
                    <div class="input-group-text bg-secondary">
                        <i class="bi bi-circle"></i>
                    </div>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($item); ?>" readonly>
                    <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                    <input type="hidden" name="temp_item_id" value="<?php echo $tempItemId; ?>">
                    <button class="btn btn-delete" type="submit"><i class="bi bi-dash-lg"></i></button>
                </div>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</form> 
   
     <label for="newItem">New Item</label> 
 <form action="./../add_checklist_item" method="post">
    <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="new_item">
        <button class="btn btn-add" type="submit"><i class="bi bi-plus-lg"></i></button>
    </div>
</form>

</div>
<!-- Fenêtre modale pour avertissement avant de quitter -->
<div class="modal fade" id="unsavedChangesModal" tabindex="-1" role="dialog" aria-labelledby="unsavedChangesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unsavedChangesModalLabel">Modifications non enregistrées</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Des modifications ont été effectuées. Êtes-vous sûr de vouloir quitter sans enregistrer ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmExitButton">Quitter sans enregistrer</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $web_root; ?>JS/modal.js"></script>


</body>
</html>
