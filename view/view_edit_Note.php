<?php
// Vérifiez que la note à éditer est passée à la vue
if (!isset($note)) {
    throw new Exception("La note à éditer n'est pas définie.");
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Éditer la Note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
   
    .btn-create-note {
        position: fixed; /* Fixed position */
        top: 20px; /* Distance from the top */
        right: 20px; /* Distance from the right */
        z-index: 1000; /* Ensure it's above other items */
        background-color: transparent; /* Fond transparent */
        border: none; /* Supprimer la bordure */
        padding: 0; /* Supprimer le rembourrage */
        cursor: pointer; /* Curseur de pointeur */
    }
    .btn-create-note img {
        width: 30px; /* Largeur de l'image */
        height: 30px; /* Hauteur de l'image */
    }
    .bi-arrow-left {
        font-size: 30px;
    position: fixed;
    top: 20px;
    left: 40px; /* Déplacer légèrement vers la droite */
    z-index: 1000;
    color: white;
    
}
.container-form {
    margin-top: 100px; /* Ajoutez plus de marge pour pousser le formulaire vers le bas */
}
#text {
        height: 300px; /* Définir une hauteur spécifique pour la zone de texte */
    }
</style>
<body>
<div>
    <a href="#"><button type="submit" class="btn-create-note">
        <img src="../css/save-icon-14.png"  />
    </button></a>
    <button class="navbar-brand btn btn-link" id="backButton" style="color: inherit; text-decoration: none;">
    <i class="bi bi-arrow-left"></i>
</button>
</div>

    <div class="container container-form"> 
        
        <form id="editTextNoteForm" action="./../save_edited_note" method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($note->id) ?>">

<div class="mb-3">
    <label for="title" class="form-label">Titre</label>
    <input type="text" class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>" id="title" name="title" value="<?= htmlspecialchars($note->title) ?>" required>
    <?php if (empty($errors['title'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
    <?php endif; ?>
</div>
<div class="mb-3">
            <label for="text" class="form-label">Contenu</label>
            <textarea class="form-control <?= !empty($errors['text']) ? 'is-invalid' : '' ?>" id="text" name="text" rows="5"><?= htmlspecialchars($note->content) ?></textarea>
            <?php if (!empty($errors['text'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['text']) ?></div>
            <?php endif; ?>
        </div>

            <button type="submit" class="btn-create-note">
            <img src="../css/save-icon-14.png" />
        </button>
          
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

