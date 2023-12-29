<?php
// Vérifiez que la note à éditer est passée à la vue
if (!isset($note)) {
    throw new Exception("La note à éditer n'est pas définie.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer la Note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
      body {
        background-color: #121212; /* Fond sombre */
        color: #e4e4e4; /* Texte clair */
    }
    .form-control {
        background-color: #222; /* Fond des champs de formulaire */
        color: white; /* Texte des champs de formulaire */
        border-color: #444; /* Bordure des champs de formulaire */
    }
    .form-label {
        color: #aaa; /* Couleur des étiquettes de formulaire */
    }
   
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
<div><a href="#"><button type="submit" class="btn-create-note">
        <img src="../css/save-icon-14.png" alt="Créer la note" />
    </button></a>
    <a href="javascript:history.back()" class="bi bi-arrow-left">
        
    </a>
</div>

    <div class="container container-form"> <!-- Utiliser des classes Bootstrap pour la mise en page -->
        <form action="./save_edited_note" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($note->id) ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($note->title) ?>" required>
            </div>

            <div class="mb-3">
                <label for="text" class="form-label">Contenu</label>
                <textarea class="form-control" id="text" name="text" rows="5"><?= htmlspecialchars($note->content) ?></textarea>
            </div>

            <button type="submit" class="btn-create-note">
            <img src="../css/save-icon-14.png" alt="Créer la note" />
        </button>
          
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

