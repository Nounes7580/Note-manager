$(document).ready(function() {
    // Faites chaque conteneur de notes "sortable"
    $(".notes-container").sortable({
        connectWith: ".notes-container",
        placeholder: "ui-state-highlight",
        start: function(e, ui){
            ui.placeholder.height(ui.item.height());
        },
        update: function(event, ui) {
            var order = [];
            // Pour chaque conteneur, obtenez l'ordre des notes
            $(".notes-container").each(function(){
                var catOrder = $(this).sortable('toArray', { attribute: 'data-id' });
            order.push(catOrder);
            });

            // Postez l'ordre général pour toutes les catégories
            console.log(order); // Vérifiez la sortie dans la console
            $.post('/path/to/your/script.php', { order: order }, function(data) {
                console.log("Order updated", data);
        });
        }
    }).disableSelection();
});


function updateNotesOrderAndPinStatus(orderedIds, isCurrentlyPinned, originalPinnedStatus, movedNoteId, dropZone) {
    $.ajax({
        url: 'Controller/updateNotesOrderAndPinStatus', 
        type: 'GET',
        data: {
            orderedIds: orderedIds,
            isCurrentlyPinned: isCurrentlyPinned,
            originalPinnedStatus: originalPinnedStatus,
            movedNoteId: movedNoteId,
            dropZone: dropZone
        },
        success: function(response) {
            console.log("Order and pin status updated successfully", response);
        },
        error: function(xhr, status, error) {
            console.error("An error occurred while updating order and pin status", error);
        }
    });
}
