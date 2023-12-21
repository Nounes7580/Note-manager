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
    <!-- Main Form for Title -->
    <form>
        <!-- Title Field -->
        <div class="mb-3">
            <label for="titleInput" class="form-label">Title</label>
            <input type="text" class="form-control" id="titleInput" 
                   placeholder="Enter title" value="<?= htmlspecialchars($note->title) ?>" readonly>
        </div>
    </form> <!-- End of Main Form -->

    <!-- Checklist Items -->
    <label for="titleInput" class="form-label">Items</label>

    <?php
        if (isset($items) && is_array($items)) {
            usort($items, function($a, $b) {
                return $a->checked <=> $b->checked;
            });
        }
    ?>

    <?php if (isset($items) && is_array($items)): ?>
        <?php foreach ($items as $item): ?>
            <!-- Separate Form for Each Checklist Item -->
            <form action="/prwb_2324_c08/Notes/check_or_uncheck_item
" method="post" class="input-group mb-2">

                <input type="hidden" name="item_id" value="<?= $item->id ?>">
                <input type="hidden" name="note_id" value="<?= $note->id ?>">

                <button type="submit" class="btn btn-primary">
                    <?php if ($item->checked): ?>
                        <i class="bi bi-check-square-fill"></i> <!-- Icon for checked -->
                    <?php else: ?>
                        <i class="bi bi-square"></i> <!-- Icon for unchecked -->
                    <?php endif; ?>
                </button>

                <input type="text" class="form-control" 
                       id="item<?= $item->id ?>" 
                       value="<?= htmlspecialchars($item->content) ?>" 
                       readonly>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</div>



    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
