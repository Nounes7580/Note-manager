<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];

?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Éditer Note Checklist</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<style>
    .btn-delete,
    .btn-add {
        color: white;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-add {
        background-color: #0d6efd;
    }







    .deleted-item {
        text-decoration: line-through;
        color: #aaa;
    }
</style>

<body>

    <script src="<?php echo $web_root; ?>/lib/jquery-3.7.1.min.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthItem = <?php echo Configuration::get('item_min_length'); ?>;
        var maxLengthItem = <?php echo Configuration::get('item_max_length'); ?>;
    </script>
    <script src="<?php echo $web_root; ?>/lib/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $web_root; ?>notes/show_note/<?php echo $note->id; ?>">
                <i class="bi bi-arrow-left"></i>
            </a>
            <a onclick="document.getElementById('checklisteditForm').submit(); return false;" style="cursor: pointer;">
                <i class="bi bi-floppy2-fill"></i>
            </a>
        </div>
    </nav>
    <div class="container mt-4">
        <form id="checklisteditForm" action="./../save_edited_checklistnote" method="post" novalidate
            onsubmit="return validateForm()">
            <input type="hidden" name="id" value="<?php echo $note->id; ?>">
            <?php if (isset($note)): ?>
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo htmlspecialchars($note->title); ?>" required onchange="validateTitle()"
                        data-original="<?php echo htmlspecialchars($note->title); ?>">

                    <div class="invalid-feedback">
                        <?php echo (!empty($errors) && isset($errors['title'])) ? htmlspecialchars($errors['title']) : ''; ?>
                    </div>
                </div>
                <?php foreach ($note->getItems() as $index => $item): ?>
                    <div class="input-group mb-3">
                        <div class="input-group-text bg-secondary">
                            <i class="bi <?php echo $item->checked ? 'bi-check-circle-fill' : 'bi-circle'; ?>"></i>
                        </div>
                        <input type="text"
                            class="form-control item-control"
                            name="items[<?php echo $item->id; ?>]" value="<?php echo htmlspecialchars($item->content); ?>"
                            required onkeyup="validateItem(this)"
                            data-original="<?php echo htmlspecialchars($item->content); ?>">
                        <div class="invalid-feedback">
                            <?php echo (!empty($errors) && isset($errors['items'][$item->id])) ? htmlspecialchars($errors['items'][$item->id]) : ''; ?>
                        </div>
                        <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                        <button class="btn btn-delete" type="button"
                            onclick="deleteItem(<?php echo $item->id; ?>, <?php echo $note->id; ?>)"><i
                                class="bi bi-dash-lg"></i></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <!-- Nouveaux éléments -->
            <?php if (isset($_SESSION['checklist_items'][$note->id])): ?>
                <?php foreach ($_SESSION['checklist_items'][$note->id] as $tempItemId => $item): ?>
                    <form action="./../delete_temporary_item" method="post" class="mb-3" novalidate>
                        <div class="input-group">
                            <div class="input-group-text bg-secondary">
                                <i class="bi bi-circle"></i>
                            </div>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($item); ?>">
                            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                            <input type="hidden" name="temp_item_id" value="<?php echo $tempItemId; ?>">
                            <button class="btn btn-delete" type="submit"><i class="bi bi-dash-lg"></i></button>
                        </div>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </form>

        <label for="newItem">New Item</label>
        <form action="./../add_checklist_item" method="post">
            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="new_item">
                <button class="btn btn-add" type="submit"><i class="bi bi-plus-lg"></i></button>
            </div>
        </form>
        <script>
            function deleteItem(itemId, noteId) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = './../delete_checklist_item';

                var inputItemId = document.createElement('input');
                inputItemId.type = 'hidden';
                inputItemId.name = 'item_id';
                inputItemId.value = itemId;
                form.appendChild(inputItemId);

                var inputNoteId = document.createElement('input');
                inputNoteId.type = 'hidden';
                inputNoteId.name = 'note_id';
                inputNoteId.value = noteId;
                form.appendChild(inputNoteId);

                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</body>

</html>