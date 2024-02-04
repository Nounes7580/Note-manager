<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<?php $pageTitle = "My Archives"; ?>  <!--  une variable pour le titre dans la navbar -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archived Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
        }
        .checkbox-item {
            display: none;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%; /* Adjust the width as needed */
        }
        .checkbox-item:nth-child(-n+3) {
            display: block;
        }
        .stretched-link {
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


    </style>

</head>
<body>
    
    <?php include('navbar.php'); ?>
    
    <div class="container mt-5">
        <?php
        $archivedNotes = array_filter($notes, function($note) {
            return $note->isArchived();
        });
        ?>

    <!-- Archived Notes -->
    <?php if (!empty($archivedNotes)): ?>
        <h2 class="mb-4">Archived</h2>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3">
            <?php foreach ($archivedNotes as $note): ?>
                <div class="col-6 col-md-4 mb-3">
                        <div class="card h-100" style="max-width: 18rem;">
                            <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                                
                            <a href="<?php echo $web_root; ?>notes/show_note/<?= $note->id ?>" class="stretched-link">
                                <div class="card-body">

                                    <?php if ($note instanceof TextNote): ?>
                                        <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                                    <?php elseif ($note instanceof CheckListNote): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($note->getItems() as $item): ?>
                                                    <div class="checkbox-item">

                                                    <input class="form-check-input me-1" type="checkbox" <?= $item->checked ? 'checked' : '' ?> disabled>
                                                    <?= htmlspecialchars($item->content) ?>
                                            </div>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>

                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No archived notes found.</p>
    <?php endif; ?>
</div>

    
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
