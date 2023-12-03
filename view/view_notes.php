<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional custom styles, if needed */
        body {
            background-color: #f8f9fa;
        }
        .note-card {
            margin-bottom: 10px;
        }
        .note-title {
            color: white;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">My Notes</h1>
        <?php if (!empty($notes)): ?>
            <div class="row">
                <?php foreach ($notes as $note): ?>
                    <div class="col-md-4">
                        <div class="card note-card">
                            <div class="card-header bg-dark note-title">
                                <?= htmlspecialchars($note->title) ?>
                            </div>
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
