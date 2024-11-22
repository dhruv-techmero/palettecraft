$(document).ready(function () {
    // Function to convert RGB color to HEX
    function rgbToHex(color) {
        if (!color.startsWith("rgb")) {
            return color.replace("#", "");
        }
        let a = color.split("(")[1].split(")")[0];
        a = a.split(",");
        let b = a.map(function (x) {
            x = parseInt(x).toString(16);
            return (x.length === 1) ? "0" + x : x;
        });
        return b.join("");
    }

    // Function to handle form submission
    function submit() {
        $('.loader').show();
        $('.buttonContainer').hide();

        let code = []; // Initialize an array to store colors
        $('.createPage .palette .place').each(function () {
            let color = rgbToHex($(this).css('background-color')); // Get hex color
            code.push(color); // Add each color to the array
        });
        code = code.join('-'); // Join the array into a string with a separator

        let tagString = [];
        $('.inputContainer .tag').each(function () {
            tagString.push($(this).attr('tag'));
        });
        tagString = tagString.join(' '); // Convert to a space-separated string

        $.post(paletteStoreUrl, {
            code: code,
            tags: tagString,
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        })
            .done(function (data) {
                // console.log(data)
                $('.loader').hide();
                if (data.status === 'success') {
                    // getSingle(data.id);
                    window.location.href = data.redirect_url;
                } else {
                    $('.error').html(data);
                    if (data.id) {
                        $('.error').html(
                            `<div class='message'>
                            This palette was already submitted. 
                            <a href='/palette/${code}'>→ View</a>
                        </div>`
                        );
                    }
                }
            })
            .fail(function (xhr, status, error) {
                $('.loader').hide();
                $('.error').html(`An error occurred: ${status}`);
            });
    }





    // Expose the function to the global scope
    window.submit = submit;


});
