<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<?php $pageTitle = "Search my notes"; // Set this before including the navbar ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container">
        <h1>Search Notes by Label</h1>
        <form method="post" action="./searchNotesByLabel">
            <select name="label" class="form-select mb-3">
                <?php
                if (!empty($labels)) {
                    foreach ($labels as $label) {
                        echo '<option value="' . htmlspecialchars($label) . '">' . htmlspecialchars($label) . '</option>';
                    }
                } else {
                    echo '<option>No labels found</option>';
                }
                ?>
            </select>
            <input type="submit" value="Search" class="btn btn-primary">
        </form>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
