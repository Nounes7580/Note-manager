function validateTitle() {
    console.log("validateTitle is called"); // To check if the function is being triggered
    var title = $('#title').val();
    if (title.length < minLengthTitle || title.length > maxLengthTitle) {
        $('#titleError').text('Title must be between ' + minLengthTitle + ' and ' + maxLengthTitle + ' characters.');
        $('#title').addClass('is-invalid');
    } else {
        $('#titleError').text('');
        $('#title').removeClass('is-invalid');
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

