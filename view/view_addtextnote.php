<?php
// add_textnote.php
// Assurez-vous que $user est défini et est l'instance de l'utilisateur actuel.
if (!isset($user)) {
    throw new Exception("User variable is not set for the view.");
}

$title = ''; // Valeur par défaut pour le titre
$text = ''; // Valeur par défaut pour le texte
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une note textuelle</title>
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
</style>
<body>
<a href="#"><button type="submit" class="btn-create-note">
        <img src="../css/save-icon-14.png" alt="Créer la note" />
    </button></a>
    <div class="container">
        <form action="" method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Titre de la note</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Entrez le titre de la note" required>
            </div>
            <div class="mb-3">
                <label for="text" class="form-label">Text</label>
                <textarea class="form-control" id="text" name="text" rows="3" placeholder="Entrez le contenu de la note" required></textarea>
            </div>
            <button type="submit" class="btn-create-note">
                <img src="../css/save-icon-14.png" alt="Créer la note" />
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>