$(document).ready(function () {
    //Form submit (Create & Update)
    $('.formSubmit').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = $(this).serialize();

        $('.loader-container').show(); // Show loader

        let thisForm = $(this);

        // Send AJAX request
        $.ajax({
            url: thisForm.attr('action'),
            type: thisForm.attr('method'),
            data: formData,
            success: function (response) {

                $('.loader-container').hide(); // Hide loader

                if (response.status === false) {
                    toastr.error(response.message);
                } else {
                    toastr.success(response.message);

                    if (thisForm.attr('method') === 'POST') {
                        $('.formSubmit').trigger('reset'); // reset form
                    }
                }
            },
            error: function (xhr) {
                $('.loader-container').hide(); // Hide loader

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Display errors under each input field
                    if (errors.hasOwnProperty('name')) {
                        $('#name').addClass('is-invalid');
                        $('.name-error').text(errors.name[0]);
                    }

                    if (errors.hasOwnProperty('email')) {
                        $('#email').addClass('is-invalid');
                        $('.email-error').text(errors.email[0]);
                    }
                }
            }
        });
    });

    //input clear
    $('.formSubmit #name').keyup(function () {
        $(this).removeClass('is-invalid');
        $('.name-error').text('');
    });

    $('.formSubmit #email').keyup(function () {
        $(this).removeClass('is-invalid');
        $('.email-error').text('');
    });
});
