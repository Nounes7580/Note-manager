<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notes</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

<?php
    if($note->isArchived()) { // Assuming $note is your note object and isArchived is a method that returns true if the note is archived
        include('view/archived_note_nav.php');
    } else {
        include('view/standard_note_nav.php');
    }
?>
 
            <form>

            <!-- Content Field -->
            <div class="mb-3">
                <label for="contentTextarea" class="form-label">Content</label>
                <textarea class="form-control" id="contentTextarea" rows="15" readonly><?= htmlspecialchars($note->content) ?></textarea>
            </div>
        <!-- Form End -->
    </form>

    <!-- Optional: Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>