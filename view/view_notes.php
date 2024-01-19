<!DOCTYPE html>

<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Limit to 3 lines */
            -webkit-box-orient: vertical;
        }

        .checkbox-item {
            display: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
            /* Adjust the width as needed */
        }

        .checkbox-item:nth-child(-n+3) {
            display: block;
        }

        .stretched-link {
<<<<<<< HEAD
    display: block; /* Ensures it behaves like a block element */
    color: inherit; /* Maintains the text color */
    text-decoration: none; /* Removes underline */
    width: 100%; /* Ensures it covers the full width */
    height: 100%; /* Ensures it covers the full height */
    position: relative; /* Adjust as necessary */
    z-index: 1; /* Brings the link to the front */
}

.stretched-link::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.bi-card-checklist {
  
    color: #F1C40F; /* Couleur du texte en noir pour un bon contraste */
    font-size: 35px; /* Augmente la taille du texte (et du bouton) */
    padding: 10px 15px; /* Espacement intérieur pour augmenter la taille du bouton */
    border: none; /* Supprime la bordure par défaut */
    border-radius: 5px; /* Arrondit les coins du bouton */
    cursor: pointer; /* Change le curseur en main lors du survol */
    
}

=======
            display: block;
            /* Ensures it behaves like a block element */
            color: inherit;
            /* Maintains the text color */
            text-decoration: none;
            /* Removes underline */
            width: 100%;
            /* Ensures it covers the full width */
            height: 100%;
            /* Ensures it covers the full height */
            position: relative;
            /* Adjust as necessary */
            z-index: 1;
            /* Brings the link to the front */
        }
>>>>>>> 70bb6527f454dbdb6fe6ac18c11f5f29fedb50fc

        .stretched-link::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
</head>

<body>

    <nav class="navbar fixed-top navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src="../css/logo.png" alt="Logo" style="height: 50px; margin-right: 10px;"> <!-- Adjust the height as needed -->
                NoteSpark
            </a>
            <div class="offcanvas offcanvas-start custom-offcanvas" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <!-- Use the Bootstrap class for text color -->
                    <h5 class="offcanvas-title text-warning" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo $web_root; ?>Notes/">My notes <span class="sr-only"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $web_root; ?>Notes/archives">My archives</a>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $web_root; ?>Main/logout">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $web_root; ?>Main/settings">Settings</a>
                        </li>
                        <!-- Add more menu items here as needed -->
                    </ul>
                    <!-- Optional: Add a search form or other elements here -->
                </div>
            </div>
        </div>
    </nav>
    <br>

    <!-- Add custom CSS to style the shorter offcanvas menu -->
    <style>
        .custom-offcanvas {
            max-width: 50%;
            /* Adjust the width to your desired value */
        }
    </style>


    <div class="container mt-5">
        <!-- Bouton pour créer une nouvelle note -->
        <div class="fixed-bottom d-flex justify-content-end p-3">
<<<<<<< HEAD
       <a href="./show_addchecklistnote" class=" bi-card-checklist" style="border-radius: 40px; padding: 10px 20px;"></a>
   
    <form action="./show_addtextnote" method="post">
        <input type="hidden" name="title" value="Nouvelle Note">
        <input type="hidden" name="text" value="Contenu de la note">
        <button type="submit" class="btn" style="border-radius: 40px; padding: 10px 20px;">
            <img src="../css/icons8-add-file-48.png" alt="Ajouter">
        </button>
    </form>
</div>
=======
            <form action="./show_addtextnote" method="post">
                <input type="hidden" name="title" value="Nouvelle Note">
                <input type="hidden" name="text" value="Contenu de la note">
                <button type="submit" class="btn" style="border-radius: 40px; padding: 10px 20px;">
                    <img src="../css/icons8-add-file-48.png" alt="Ajouter">
                </button>
            </form>
        </div>
>>>>>>> 70bb6527f454dbdb6fe6ac18c11f5f29fedb50fc

        <!-- Pinned Notes -->
        <?php if (!empty($pinnedNotes)) : ?>
            <h2 class="mb-4">Pinned</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3">
                <?php foreach ($pinnedNotes as $note) : ?>
                    <div class="col-6 col-md-4 mb-3">
                        <div class="card h-100" style="max-width: 18rem;">
                            <div class="card-header"><?= htmlspecialchars($note->title) ?></div>

                            <a href="./show_note/<?= $note->id ?>" class="stretched-link">
                                <div class="card-body">

                                    <?php if ($note instanceof TextNote) : ?>
                                        <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                                    <?php elseif ($note instanceof CheckListNote) : ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($note->getItems() as $item) : ?>
                                                <div class="checkbox-item">

                                                    <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                                    <?= htmlspecialchars($item->content) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                            </a>

                            <div class="card-footer">
                                <!-- Display Move Left Button if not at extreme left -->
                                <?php if ($note->getPreviousNote() !== null) : ?>
                                    <form action="./moveNoteLeft" method="post" class="float-start">
                                        <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                        <button type="submit" class="btn btn-link text-light-blue">
                                            <i class="bi bi-arrow-left-circle"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>



                                <!-- Display Move Right Button if not at extreme right -->
                                <?php if ($note->getNextNote() !== null) : ?>
                                    <form action="./moveNoteRight" method="post" class="float-end">
                                        <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                        <button type="submit" class="btn btn-link text-light-blue">
                                            <i class="bi bi-arrow-right-circle"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Other Notes -->
        <?php if (!empty($otherNotes)) : ?>
            <h2 class="mb-4">Others</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3">
                <?php foreach ($otherNotes as $note) : ?>
                    <?php if (!$note->isArchived()) : ?>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card h-100" style="max-width: 18rem;">
                                <div class="card-header"><?= htmlspecialchars($note->title) ?></div>

                                <a href="./show_note/<?= $note->id ?>" class="stretched-link">
                                    <div class="card-body">
                                        <?php if ($note instanceof TextNote) : ?>
                                            <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                                        <?php elseif ($note instanceof CheckListNote) : ?>
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($note->getItems() as $item) : ?>
                                                    <div class="checkbox-item">
                                                        <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                                        <?= htmlspecialchars($item->content) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <div class="card-footer">
                                    <!-- Display Move Left Button if not at extreme left -->
                                    <?php if ($note->getPreviousNote() !== null) : ?>
                                        <form action="./moveNoteLeft" method="post" class="float-start">
                                            <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                            <button type="submit" class="btn btn-link text-light-blue">
                                                <i class="bi bi-arrow-left-circle"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>



                                    <!-- Display Move Right Button if not at extreme right -->
                                    <?php if ($note->getNextNote() !== null) : ?>
                                        <form action="./moveNoteRight" method="post" class="float-end">
                                            <input type="hidden" name="noteId" value="<?= $note->id ?>">
                                            <button type="submit" class="btn btn-link text-light-blue">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php elseif (empty($pinnedNotes)) : ?>
            <p>No notes found.</p>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>