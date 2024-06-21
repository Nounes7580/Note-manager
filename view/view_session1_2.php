<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <form method="post" action="<?= $web_root ?>/Session1_2/show">
        <h2>Users</h2>
        <select name="source_user">
            <option value="0">-- Select a Source User --</option>
            <?php foreach ($allUsers as $user): ?>
                <option value="<?= htmlspecialchars($user->id) ?>" <?= isset($selectedUser1) && $selectedUser1 == $user->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user->full_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="target_user">
            <option value="0">-- Select a Target User --</option>
            <?php foreach ($allUsers as $user): ?>
                <option value="<?= htmlspecialchars($user->id) ?>" <?= isset($selectedUser2) && $selectedUser2 == $user->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user->full_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">OK</button>
    </form>

    <?php if (isset($selectedUser1) && isset($selectedUser2) && $selectedUser1 != '0' && $selectedUser2 != '0' && $selectedUser1 != $selectedUser2): ?>
        <form id="notes-form" method="post" action="<?= $web_root ?>/Session1_2/transfer">
            <input type="hidden" name="source_user" value="<?= htmlspecialchars($selectedUser1) ?>">
            <input type="hidden" name="target_user" value="<?= htmlspecialchars($selectedUser2) ?>">
            <div id="notes-container">
                <h3>Notes for Users :</h3>
                <div>
                    <div style="display:inline-block; width:45%">
                        <?php foreach ($notesUser1 as $note): ?>
                            <div>
                                <input name="note_id" type="radio" value="<?= htmlspecialchars($note->id) ?>">
                                <label><?= htmlspecialchars($note->title) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div style="display:inline-block; width:45%">
                        <?php foreach ($notesUser2 as $note): ?>
                            <div>
                                <input name="note" type="radio" disabled>
                                <label><?= htmlspecialchars($note->title) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <br>
            <div>
                <button id="transfer-button" type="submit" disabled>Move selected note to target User</button>
            </div>
        </form>
    <?php else: ?>
        <div>
            <h3>You must select two different users</h3>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Enable/Disable transfer button based on radio button selection
            $('input[name="note_id"]').on('change', function () {
                $('#transfer-button').prop('disabled', !$('input[name="note_id"]:checked').length);
            });

            $('#notes-form').on('submit', function (event) {
                event.preventDefault(); // Prevent default form submission
                var formData = $(this).serialize();

                $.ajax({
                    url: '<?= $web_root ?>/Session1_2/transfer',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        $('#notes-container').html($(response).find('#notes-container').html());
                    },
                    error: function () {
                        console.error('An error occurred during the AJAX request');
                    }
                });
            });
        });
    </script>
</body>

</html>
