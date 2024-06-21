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
	<div class="main">
		<form id="user-selection-form" action="<?php echo $web_root; ?>/Session1/show" method="post">
			<h1><?= htmlspecialchars($selectedName) ?></h1>
			<select name="selected_user">
				<?php if ($selectedUser) { ?>
					<option value="0"><?= htmlspecialchars($selectedName) ?></option>
				<?php } else { ?>
					<option value="0">-- Select a User --</option>
				<?php } ?>

				<?php foreach ($allUsers as $user): ?>
					<option value="<?= htmlspecialchars($user->id) ?>"><?= htmlspecialchars($user->full_name) ?></option>
				<?php endforeach; ?>
			</select>
			<button>OK</button>
		</form>
		<div id="notes-container">
			<h6>Checklist Notes for this User :</h6>
			<form method="post" action="<?php echo $web_root; ?>/Session1/toggleItemsByNotes" class="row g-3"
				id="notes-form">
				<!-- on recup l'user selectionné -->
				<input type="hidden" name="selected_user" value="<?= $selectedUser ?>">

				<ul>
				<!-- affichage des note de l'user si elles sont des checklistnotes -->

				<?php foreach ($notesOfUser as $note): ?>
						<?php if ($note instanceof CheckListNote): ?>
							<li>
								<input class="form-check-input" name="notes[]" type="checkbox"
									value="<?= htmlspecialchars($note->id) ?>"><?= htmlspecialchars($note->title) ?>
								(<?php echo $note->countItemsNotChecked() ?> / <?php echo $note->countItems() ?>)
							</li>
						<?php endif; ?>
					<?php endforeach; ?>				</ul>
				<button disabled type="submit" id="toggle-button">Toggle check for all items of selected notes</button>
			</form>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@2.9.2/dist/js/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		$(document).ready(function () {
			function bindEvents() {
				// Enable/Disable toggle-button based on checkbox selection
				$('input[name="notes[]"]').on('change', function () {
					if ($('input[name="notes[]"]:checked').length > 0) {
						$('#toggle-button').prop('disabled', false);
					} else {
						$('#toggle-button').prop('disabled', true);
					}
				});

				$('#notes-form').off('submit').on('submit', function (event) {
					event.preventDefault(); // Prevent default form submission
					var formData = $(this).serialize();

					$.ajax({
						url: '<?php echo $web_root; ?>/Session1/toggleItemsByNotes',
						type: 'POST',
						data: formData,
						success: function (response) {
							$('#notes-container').html($(response).find('#notes-container').html());
							// Re-bind the events to the newly loaded content
							bindEvents();
						},
						error: function () {
							console.error('An error occurred during the AJAX request');
						}
					});
				});
			}

			bindEvents(); // Initial binding of events
		});
	</script>
</body>

</html>
