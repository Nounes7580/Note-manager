function checkForErrors() {
    if ($('.is-invalid').length > 0) {
        $('.bi-floppy2-fill').parent().addClass('disabled').css('pointer-events', 'none');
    } else {
        $('.bi-floppy2-fill').parent().removeClass('disabled').css('pointer-events', '');
    }
}
function trySubmitForm() {
    if (!$("#saveButton").hasClass('disabled')) {
        document.getElementById('checklisteditForm').submit();
    } else {
        console.log('Form has errors and cannot be submitted.');
    }
}
function updateAddButtonState() {
    var hasErrors = $('.is-invalid').length > 0;
    var addButton = $('.btn-add'); // Assuming this is the class for your "+" button

    if (hasErrors) {
        addButton.prop('disabled', true); // Disable the button if there are errors
    } else {
        addButton.prop('disabled', false); // Enable the button if no errors
    }
}

function disableEnterKeySubmission() {
    $('.form-control').keypress(function(event) {
        if (event.which === 13) { // 13 is the Enter key
            var form = $(this).closest('form'); // Get the closest form element
            if (form.find('.is-invalid').length > 0) {
                event.preventDefault(); // Prevent form submission if there are invalid fields
            }
        }
    });
}
function validateTitle() {
    var titleInput = $("#title");
    var titleError = titleInput.next('.invalid-feedback');
    var titleValue = titleInput.val().trim();

    titleError.html("");
    titleInput.removeClass("is-invalid");
    titleInput.removeClass("is-valid");

    if (titleValue.length < minLengthTitle || titleValue.length > maxLengthTitle) {
        titleError.html("Title must be between " + minLengthTitle + " and " + maxLengthTitle + " characters long.");
        titleInput.addClass("is-invalid");
    } else {
        $.ajax({
            url: baseURL + 'notes/check_title_uniqueness',
            type: 'POST',
            data: {
                title: titleValue,
                noteId: $("#title").data('note-id')
            },
            success: function(data) {
                console.log('Success callback data:', data);
                console.log('Type of isUnique:', typeof data.isUnique);
            
                if(data.isUnique === true) {
                    console.log('Adding is-valid class');
                    titleInput.addClass("is-valid");
                } else if(data.isUnique === false){
                    console.log('Adding is-invalid class because title is not unique');
                    titleError.html("Title must be unique among all notes.");
                    titleInput.addClass("is-invalid");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error callback:', textStatus, errorThrown);
                titleError.html("Error checking title uniqueness.");
                titleInput.addClass("is-invalid");
            }
        });
    }
    checkForErrors();


}


function validateItem(itemElement) {
    var item = $(itemElement);
    var content = item.val();
    var errorElement = item.next('.invalid-feedback');

    if (content !== item.data('original')) {
        errorElement.text("");
        item.removeClass("is-invalid");
        item.removeClass("is-valid");

        if (content.length < minLengthItem || content.length > maxLengthItem) {
            errorElement.text("Content must be between " + minLengthItem + " and " + maxLengthItem + " characters.");
            item.addClass("is-invalid");
        } else {
            var duplicates = [];
            $('.item-control').each(function() {
                if (this !== itemElement && $(this).val() === content) {
                    duplicates.push(this);
                }
            });

            if (duplicates.length > 0) {
                errorElement.text("Item content must be unique within the same note.");
                item.addClass("is-invalid");
                duplicates.forEach(function(duplicate) {
                    $(duplicate).addClass("is-invalid");
                    $(duplicate).next('.invalid-feedback').text("Item content must be unique within the same note.");
                });
            } else {
                item.addClass("is-valid");
            }
        }
    }
    checkForErrors();
    updateAddButtonState(); // Update the "+" button state after validation


}

$(document).ready(function() {
    // Store original values for validation comparison
    $("#title").data('original', $("#title").val());
    $(".item-control").each(function() {
        $(this).data('original', $(this).val());
    });

    // Event bindings for title and item validation
    $("#title").on('input', function() {
        validateTitle();
        checkForErrors();
    });
    $(".item-control").each(function() {
        $(this).on("input", function() {
            validateItem(this);
            checkForErrors();
        });
    });

    // Prevent form submission with Enter key based on validation errors
    disableEnterKeySubmission();
});
