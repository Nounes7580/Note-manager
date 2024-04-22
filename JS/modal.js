$('.deleteNote').on('click', function() {
    var noteId = $(this).data('note-id');
    console.log('Setting noteId:', noteId); 
    $('#confirmDelete').data('noteId', noteId);
    
});

$(document).ready(function() {
    
    $('#confirmDelete').click(function() {
        
        var noteId = $(this).data('noteId');  
        if (!noteId) {
            console.error("Note ID is undefined or null");
            return; 
        }
    
        
        $.ajax({
            url: 'http://localhost/prwb_2324_c08/notes/delete_note', 
            type: 'POST',
            data: { noteId: noteId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#resultModal').modal('show');
                    
                } else {
                    console.error("Server error: " + response.error);
                }
            },
            error: function(xhr) {
                console.error("AJAX error: ", xhr.responseText);
            }
        });
    });

    
    $('#resultModal').on('hidden.bs.modal', function () {
        window.location.reload();  
    });
});

$(document).ready(function() {
    var formOriginalData = $("#editTextNoteForm, #editCheckListNoteForm").serialize();
    var unsavedChangesModal = new bootstrap.Modal(document.getElementById('unsavedChangesModal'), {
        keyboard: false
    });

    // Fonction pour vérifier si des modifications ont été effectuées
    function checkFormChanges() {
        return formOriginalData != $("#editTextNoteForm, #editCheckListNoteForm").serialize();
    }
    $("#backButton").click(function(e) {
        console.log("Back button clicked");
        if (formHasChanged()) {
            console.log("Form has changed");
            e.preventDefault();
            $('#unsavedChangesModal').modal('show');
        } else {
            console.log("No changes detected");
            window.history.back();
        }
    });

 $("#confirmExitButton").click(function() {
    window.history.back(); 
});
$("#cancelButton").click(function() {
    
});

$('#unsavedChangesModal').on('hidden.bs.modal', function () {
  
});
    function formHasChanged() {
        console.log("Checking form changes...");
        return $("#editTextNoteForm, #checklisteditForm").serialize() !== formOriginalData;
    }
  
});



