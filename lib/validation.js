function validateTitle() {
    var titleInput = document.getElementById("title");
    var titleError = titleInput.nextElementSibling;
    var titleValue = titleInput.value;

    titleError.innerHTML = "";
    titleInput.classList.remove("is-invalid");
    titleInput.classList.remove("is-valid");

    if (titleValue.length < minLengthTitle || titleValue.length > maxLengthTitle) {
        titleError.innerHTML = "Title must be between " + minLengthTitle + " and " + maxLengthTitle + " characters long.";
        titleInput.classList.add("is-invalid");
    } else {
        titleInput.classList.add("is-valid");
    }
}

function validateItem(itemElement) {
    var content = itemElement.value;
    var errorElement = itemElement.nextElementSibling;

    errorElement.textContent = "";
    itemElement.classList.remove("is-invalid");
    itemElement.classList.remove("is-valid");

    if (content.length < minLengthItem || content.length > maxLengthItem) {
        errorElement.textContent = "Content must be between " + minLengthItem + " and " + maxLengthItem + " characters.";
        itemElement.classList.add("is-invalid");
    } else {
        itemElement.classList.add("is-valid");
    }
}
$(document).ready(function () {
    $("#title").keyup(validateTitle).trigger('keyup');

    // Attach the event handler directly within the ready function
    $(".item-control").each(function() {
        $(this).on("keyup change", function() {
            validateItem(this); // Now `validateItem` is properly recognized
        }).trigger('keyup').trigger('change');
    });
});
