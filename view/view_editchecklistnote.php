<?php
$formSubmitted = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
$validFields = $validFields ?? [];

?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>" />

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

    .mb-3 {
        /* Existing class for margin-bottom, consider increasing if needed */
        margin-bottom: 1rem;
        /* You can increase this value as needed */
    }


    .input-group .form-control {
        flex: 1 0 auto;
        /* Ensure that the input field flexes to fill available space */
    }

    .input-group .btn {
        flex-shrink: 0;
        /* Prevent the button from shrinking */
    }

    .input-group .invalid-feedback {
        width: 100%;
        /* Make sure the feedback takes the full width */
        position: absolute;
        /* Positioning it absolutely to avoid taking up space */
        bottom: -10px;
        /* Position below the input field */
        left: 0;
        font-size: 0.875em;
        /* Smaller font size for error messages */
    }

    .input-group {
        position: relative;
        /* Relative positioning to position the feedback absolutely within */
        margin-bottom: 40px;
        /* Increase margin to accommodate the feedback message */
        padding-bottom: 1rem;
        /* Adds padding at the bottom for clearer separation */

    }

    .invalid-feedback:empty {
        display: none !important;
    }

    .invalid-feedback {
        display: block !important;
        /* Temporarily ensure it's always visible when not empty */
    }

    .disabled {
        pointer-events: none;
        /* This prevents the button from receiving click events */
        opacity: 0.5;
        /* Dim the button to indicate it's disabled */
        filter: blur(1px);
        /* Optional: add a blur effect to make it look disabled */
    }

    .deleted-item {
        text-decoration: line-through;
        color: #aaa;
    }
</style>

<body>

    <script src="lib/jquery-3.7.1.min.js"></script>
    <script>
        var minLengthTitle = <?php echo Configuration::get('title_min_length'); ?>;
        var maxLengthTitle = <?php echo Configuration::get('title_max_length'); ?>;
        var minLengthItem = <?php echo Configuration::get('item_min_length'); ?>;
        var maxLengthItem = <?php echo Configuration::get('item_max_length'); ?>;
    </script>
    <script>
        var baseURL = '<?php echo $web_root; ?>';
    </script>
    <script src="lib/validation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="notes/show_note/<?php echo $note->id; ?>">
                <i class="bi bi-arrow-left"></i>
            </a>
            <a id="saveButton" onclick="trySubmitForm();" style="cursor: pointer;">
                <i class="bi bi-floppy2-fill"></i>
            </a>
        </div>
    </nav>
    <div class="container mt-4">
        <form id="checklisteditForm" action="notes/save_edited_checklistnote" method="post">
            <input type="hidden" name="id" value="<?php echo $note->id; ?>">
            <?php if (isset($note)): ?>
                <label for="title">Title</label>

                <div class="mb-3 <?php echo !empty($errors['title']) ? 'is-invalid' : ''; ?>">
                    <input type="text" class="form-control" id="title" name="title"
                        value="<?php echo html_entity_decode($note->title); ?>" required onchange="validateTitle()"
                        onkeyup="validateTitle()" data-original="<?php echo html_entity_decode($note->title); ?>">
                    <div class="invalid-feedback"></div>

                    <?php if (!empty($errors['title'])): ?>
                        <div class="invalid-feedback">
                            <?php echo html_entity_decode($errors['title']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <label for="items">Items</label>

                <?php foreach ($note->getItems() as $index => $item): ?>
                    <div class="input-group mb-3">
                        <div class="input-group-text bg-secondary">
                            <i class="bi <?php echo $item->checked ? 'bi-check-circle-fill' : 'bi-circle'; ?>"></i>
                        </div>
                        <!-- Add the is-invalid class conditionally based on the presence of an error for this item -->
                        <input type="text"
                            class="form-control item-control <?php echo !empty($errors['items'][$item->id]) ? 'is-invalid' : ''; ?>"
                            name="items[<?php echo $item->id; ?>]" value="<?php echo html_entity_decode($item->content); ?>"
                            required onkeyup="validateItem(this)"
                            data-original="<?php echo html_entity_decode($item->content); ?>">
                        <div class="invalid-feedback"></div>

                        <!-- Conditionally display the error message -->
                        <?php if (!empty($errors['items'][$item->id])): ?>
                            <div class="invalid-feedback">
                                <?php echo html_entity_decode($errors['items'][$item->id]); ?>
                            </div>
                        <?php endif; ?>
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
                    <form action="notes/delete_temporary_item" method="post" class="mb-3" novalidate>
                        <div class="input-group">
                            <div class="input-group-text bg-secondary">
                                <i class="bi bi-circle"></i>
                            </div>
                            <input type="text" class="form-control" value="<?php echo html_entity_decode($item); ?>">
                            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
                            <input type="hidden" name="temp_item_id" value="<?php echo $tempItemId; ?>">
                            <button class="btn btn-delete" type="submit"><i class="bi bi-dash-lg"></i></button>
                        </div>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </form>

        <label for="newItem">New Item</label>
        <form action="notes/add_checklist_item" method="post">
            <input type="hidden" name="note_id" value="<?php echo $note->id; ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control item-control" name="new_item" required
                    onkeyup="validateItem(this)">
                <div class="invalid-feedback"></div>
                <button class="btn btn-add" type="submit"><i class="bi bi-plus-lg"></i></button>
            </div>
        </form>
        <script>
            function deleteItem(itemId, noteId) {
                var form = document.createElement('form');
                form.method = 'post';
                form.action = 'notes/delete_checklist_item';

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