<!DOCTYPE html>
<html lang="en">
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Add this line -->

    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>

    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h1 class="mb-4">My Notes</h1>
    <?php if (!empty($notes)): ?>
        <div class="row">
            <?php foreach ($notes as $note): ?>
                <!-- Use 'col-6' for two cards per line on small devices and 'col-md-4' for three cards per line on medium devices and up -->
                <div class="col-6 col-md-4 mb-3">
                    <div class="card text-bg-dark" style="max-width: 18rem;">
                        <div class="card-header"><?= htmlspecialchars($note->title) ?></div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($note->content)) ?></p>
                            <!-- Add other note details here -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No notes found.</p>
    <?php endif; ?>
</div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
