$('.notes-container').sortable({
    connectWith: '.notes-container', // Permet de connecter des conteneurs entre eux
    items: '> div', // Ne permet que les divs directs à l'intérieur de .notes-container à être déplaçables
    placeholder: 'sortable-placeholder',
    forcePlaceholderSize: true,
    opacity: 0.9,
    start: function(event, ui) {
        // Capture l'état initial de la note
        ui.item.data('original-pinned-status', ui.item.closest('[data-pinned]').data('pinned'));
    },
    stop: function(event, ui) {
        var orderedIds = $(this).sortable('toArray', { attribute: 'data-id' });
        var movedNoteId = ui.item.data('id');
        var originalPinnedStatus = ui.item.data('original-pinned-status');
        var isCurrentlyPinned = ui.item.closest('[data-pinned]').data('pinned') === true;
        var dropZone = ui.item.closest('.notes-container').data('pinned') ? 'pinned-notes' : 'other-notes';
        console.log("Ordered IDs:", orderedIds);
        console.log("Moved Note ID:", movedNoteId);
        console.log("Original Pinned Status:", originalPinnedStatus);
        console.log("Is Currently Pinned:", isCurrentlyPinned);
        console.log("Drop Zone:", dropZone);
        updateNotesOrderAndPinStatus(orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone);
    }
});

function updateNotesOrderAndPinStatus(orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone) {
    $.ajax({
        url: 'http://localhost/prwb_2324_c08/notes/updateNotesOrderAndPinStatus',
        type: 'POST',
        data: {
            orderedIds: orderedIds,
            isCurrentlyPinned: isCurrentlyPinned,
            originalPinnedStatus: originalPinnedStatus,
            movedNoteId: movedNoteId,
            dropZone: dropZone,
        },
        success: function(response) {
            console.log("Success:", response);
        },
        error: function(xhr, status, error) {
            console.error("An error occurred:", xhr, status, error);
        }
    });
}


