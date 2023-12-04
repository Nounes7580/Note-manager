<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .card-header
        {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body class="bg-dark text-white">
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
