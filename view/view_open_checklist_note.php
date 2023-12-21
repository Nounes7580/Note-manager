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
    <div class="container mt-5">
<?php
// Assuming $note->created_at is a DateTime object
$now = new DateTime();
$interval = $now->diff($note->created_at);

// Function to format the interval into a human-readable format
function formatTimeDifference($dateTime) {
    if ($dateTime === null) {
        return null;
    }
    $now = new DateTime();
    $interval = $now->diff($dateTime);

    if ($interval->y > 0) {
        return $interval->y . " year" . ($interval->y > 1 ? "s" : "");
    } elseif ($interval->m > 0) {
        return $interval->m . " month" . ($interval->m > 1 ? "s" : "");
    } elseif ($interval->d > 0) {
        return $interval->d . " day" . ($interval->d > 1 ? "s" : "");
    } elseif ($interval->h > 0) {
        return $interval->h . " hour" . ($interval->h > 1 ? "s" : "");
    } elseif ($interval->i > 0) {
        return $interval->i . " minute" . ($interval->i > 1 ? "s" : "");
    } else {
        return $interval->s . " second" . ($interval->s > 1 ? "s" : "");
    }
}


// Display the time difference in italic
$createdAtMessage = "<i>Created " . formatTimeDifference($note->created_at) . " ago</i>";
$editedAtMessage = $note->edited_at ? "<i>Edited " . formatTimeDifference($note->edited_at) . " ago</i>" : "";

echo "<p>$createdAtMessage $editedAtMessage</p>";

?>
<div class="container py-4">
    <form>
        <!-- Title Field -->
        <div class="mb-3">
            <label for="titleInput" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleInput" 
                   placeholder="Enter title" value="<?= htmlspecialchars($note->title) ?>" readonly>
        </div>

        <!-- Checklist Items -->
        <label for="titleInput" class="form-label">Items</label>

        <?php if (isset($items) && is_array($items)): ?>
            <?php foreach ($items as $item): ?>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" 
                           id="item<?= $item->id ?>" <?= $item->checked ? 'checked' : '' ?>>
                    <label class="form-check-label" for="item<?= $item->id ?>">
                        <?= htmlspecialchars($item->content) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </form>
</div>


    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
