function validateForm() {
    var isValid = true;

    // Validate the title
    isValid = validateTitle() && isValid;

    // Validate each item
    $('.item-control').each(function() {
        isValid = validateItem(this) && isValid;
    });

    return isValid;
}
function validateTitle() {
    var titleInput = $("#title");
    var titleError = titleInput.next('.invalid-feedback');
    var titleValue = titleInput.val();

    if (titleValue !== titleInput.data('original')) {
        titleError.html("");
        titleInput.removeClass("is-invalid");
        titleInput.removeClass("is-valid");

        if (titleValue.length < minLengthTitle || titleValue.length > maxLengthTitle) {
            titleError.html("Title must be between " + minLengthTitle + " and " + maxLengthTitle + " characters long.");
            titleInput.addClass("is-invalid");
        } else {
            var isUnique = true;
            $('.title-control').each(function() {
                if (this !== titleInput[0] && $(this).val() === titleValue) {
                    isUnique = false;
                    return false; // break the loop
                }
            });

            if (!isUnique) {
                titleError.html("Title must be unique among all notes.");
                titleInput.addClass("is-invalid");
            } else {
                titleInput.addClass("is-valid");
            }
        }
    }
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
}

$(document).ready(function () {
    // Store original values for validation comparison
    $("#title").data('original', $("#title").val());
    $(".item-control").each(function() {
        $(this).data('original', $(this).val());
    });

    // Event bindings
    $("#title").on('input', validateTitle);
    $(".item-control").each(function() {
        $(this).on("input", function() {
            validateItem(this);
        });
    });
});
