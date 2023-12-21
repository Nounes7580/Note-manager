<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .card-text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
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


        <?php if (!empty($archivedNotes)): ?>
            <h2 class="mb-4">Archived</h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 g-md-2 g-lg-3">
                <?php foreach ($archivedNotes as $note): ?>
                    <div class="col-6 col-md-4 mb-3">
                        <div class="card h-100" style="max-width: 18rem;">
                            <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                            <div class="card-body">
                                <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (empty($pinnedNotes)): ?>
             <p>No notes found.</p>
        <?php endif; ?>

    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
