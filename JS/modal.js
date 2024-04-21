document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('deleteNote');
    deleteButton.addEventListener('click', function() {
        const noteId = document.getElementById('confirmDelete').getAttribute('data-note-id');
        fetch('/notes/delete_note', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `note_id=${noteId}`
        })
        .then(response => {
            if (response.ok) {
                $('#deleteConfirmationModal').modal('hide');
                $('#resultModal').modal('show');
                document.getElementById('resultMessage').textContent = 'La note a été supprimée avec succès.';
            } else {
                throw new Error('Something went wrong on the server.');
            }
        })
        .catch(error => {
            $('#deleteConfirmationModal').modal('hide');
            $('#resultModal').modal('show');
            document.getElementById('resultMessage').textContent = 'Erreur lors de la suppression de la note.';
        });
    });
});