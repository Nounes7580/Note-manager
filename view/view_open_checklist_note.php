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

    <?php include('utils/util_dates.php'); ?>


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
