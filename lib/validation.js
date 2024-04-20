function validateTitle() {
    var titleInput = document.getElementById('title');
    var titleError = document.querySelector('.invalid-feedback');
    var titleValue = titleInput.value;

    // Clear any existing error message
    titleError.innerHTML = '';
    titleInput.classList.remove('is-invalid');

    // Validate the title
    if (titleValue.length < minLengthTitle || titleValue.length > maxLengthTitle) {
        // Add the error message to the titleError div
        titleError.innerHTML = 'Title must be between ' + minLengthTitle + ' and ' + maxLengthTitle + ' characters long.';

        // Add the 'is-invalid' class to the title input field
        titleInput.classList.add('is-invalid');
    }
}

$(document).ready(function() {
    $('#title').keyup(validateTitle); 
    

    function addItem() {
        var newItemIndex = $('#itemsContainer .item').length + 1;
        $('#itemsContainer').append(
            '<div class="input-group mb-3 item" id="item' + newItemIndex + '">' +
            '<input type="text" class="form-control" onkeyup="validateItemContent(this)">' +
            '<button onclick="deleteItem(' + newItemIndex + ')">Delete</button>' +
            '<div class="error"></div>' +
            '</div>'
        );
    }

    window.validateItemContent = function(itemElement) {
        var content = $(itemElement).val();
        var errorElement = $(itemElement).next('.error');
        if (content.length < minLengthItem || content.length > maxLengthItem) {
            errorElement.text('Content must be between ' + minLengthItem + ' and ' + maxLengthItem + ' characters.');
            $(itemElement).css('border', '2px solid red');
        } else {
            errorElement.text('');
            $(itemElement).css('border', '2px solid green');
        }
    }
});

