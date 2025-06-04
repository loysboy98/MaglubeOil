document.addEventListener('DOMContentLoaded', function () {
    var splide = new Splide('.splide', {
        type: 'loop',
        autoplay: true,
        perPage: 1,
        perMove: 1,
        pagination: false,
        arrows: false,
        breakpoints: {
            768: {
                perPage: 1,
            },
        }
    });
    splide.mount();
});
document.addEventListener('DOMContentLoaded', function () {
    var splide = new Splide('.splide_two', {
        type: 'loop',
        autoplay: true,
        perPage: 1,
        perMove: 1,
        breakpoints: {
            768: {
                perPage: 1,
            },
        }
    });
    splide.mount();
});

$(document).ready(function () {
    $('#contactForm').submit(function (e) {
        e.preventDefault();
        $('.wrapper').fadeIn();

        var $form = $(this);
        var formData = $form.serialize();

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: formData,
            success: function (response) {
                console.log(response);
                $form[0].reset();
                $('.wrapper').fadeOut();
                $('.successfully').removeClass('d-none')
                setTimeout(() => {
                    $('.successfully').addClass('d-none');
                }, 5000);
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    handleValidationErrors(xhr.responseJSON.errors);
                    $('.wrapper').fadeOut();
                } else {
                    console.log('Error:', error);
                    $('.wrapper').fadeOut();
                }
            }
        });
    });

    function handleValidationErrors(errors) {
        $('.alert-danger').remove();
        var errorHtml =
            '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="position: fixed; top:0px; left:37.5%;width:25%;">';
        $.each(errors, function (key, value) {
            errorHtml += '<label for="">' + value + '</label>';
        });
        errorHtml +=
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $('body').append(errorHtml);
    }
});

$(document).ready(function () {
    var words = 200;
    var width = window.innerWidth;

    if (width <= 2650) {
        words = 600;
    }
    if (width <= 2200) {
        words = 300;
    }
    if (width <= 2100) {
        words = 250;
    }
    if (width <= 1920) {
        words = 210;
    }
    if (width <= 1600) {
        words = 150;
    }
    if (width <= 1400) {
        words = 120;
    }
    if (width <= 1300) {
        words = 100;
    }

    var maxWords = words; // Set the maximum number of words
    $('.truncate').each(function () {
        var text = $(this).text().split(' '); // Split the text into words
        if (text.length > maxWords) { // Check if the number of words exceeds the maximum
            var truncatedText = text.slice(0, maxWords).join(' ') + '...'; // Join the first maxWords words and add ellipsis
            $(this).text(truncatedText); // Set the truncated text
        }
    });
});

// Get the button:
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction()
};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
$('#termsButton').click(function (e) {
    e.preventDefault();

    var url = $(this).data('url');

    $.ajax({
        url: '/conditions',
        type: 'GET',
        success: function (conditions) {
            var html = '';
            for (var i = 0; i < conditions.length; i++) {
                if (conditions[i].title) {
                    html += '<p class="mx-5 " style="color:#A49D00">' + conditions[i].title +
                        '</p>';
                }
                html += '<p >' + conditions[i].description +
                    '</p>';
            }
            $('#result').html(
                html);
        }

        ,
        error: function (xhr, status, error) {
            // Handle the error
            console.log('An error occurred while fetching conditions:', error);
        }
    });
});
$('#faqsButton').click(function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $.ajax({
        url: '/faqs',
        type: 'GET',
        success: function (faqs) {
            var html = '';
            for (var i = 0; i < faqs.length; i++) {
                html += `
                            <div class="accordion-item">
                                <h2 class="accordion-header" style="border-bottom: 1px solid black">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${i}" aria-expanded="true" aria-controls="collapse${i}">
                                    ${faqs[i].question}
                                </button>
                                </h2>
                                <div id="collapse${i}" class="accordion-collapse collapse ${i === 0 ? 'show' : ''}" data-bs-parent="#result3">
                                <div class="accordion-body">
                                    ${faqs[i].answer}
                                </div>
                                </div>
                            </div>
                            `
            }
            $('#result3').html(
                html);
        }

        ,
        error: function (xhr, status, error) {
            // Handle the error
            console.log('An error occurred while fetching conditions:', error);
        }
    });
});
$('#privacyButton').click(function (e) {
    e.preventDefault();

    var url = $(this).data('url');

    $.ajax({
        url: '/policies',
        type: 'GET',
        success: function (policies) {
            console.log(policies)

            var html = '';

            for (var i = 0; i < policies.length; i++) {
                if (policies[i].title) {
                    html += '<p class="mx-5 " style="color:#A49D00">' + policies[i].title +
                        '</p>';
                }
                html += '<p >' + policies[i].description +
                    '</p>';
            }
            $('#result2').html(
                html);
        }

        ,
        error: function (xhr, status, error) {
            // Handle the error
            console.log('An error occurred while fetching policies:', error);
        }
    });
});

window.onload = function () {
    $('.wrapper').fadeOut();

}


var offset = 0;
var width = window.innerWidth;
var height = window.innerHeight;

if (width <= 2560) {
    offset = 275;
}
if (width <= 2048) {
    offset = 225;
}
if (width <= 1920) {
    offset = 150;
}
if (width <= 1366) {
    offset = 140;
}
if (width <= 1280) {
    offset = 125;
}
if (width <= 500) {
    offset = 75;
}
if (width <= 350) {
    offset = 50;
}

AOS.init({
    offset: offset,
    once: true,
});
console.log(offset, width, height);

$(document).ready(function () {
    $('#subscribe-form').submit(function (e) {
        e.preventDefault();
        $('.wrapper').fadeIn();
        var $form = $(this);
        var formData = $form.serialize();
        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: formData,
            success: function (response) {
                $form[0].reset();
                $('.wrapper').fadeOut();
                $('.successfully').removeClass('d-none')
                $('.alert-danger').addClass('d-none');
                setTimeout(() => {
                    $('.successfully').addClass('d-none');
                }, 5000);
            },
            error: function (xhr, status, error) {
                if (xhr.status) {
                    handleValidationErrors(xhr.responseJSON.error);
                    $('.wrapper').fadeOut();
                }
            }
        });
    });

    function handleValidationErrors(errors) {
        $('.alert-danger').remove();
        var errorHtml =
            '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="position: fixed; top:0px; left:37.5%;width:25%;">';
        errorHtml += '<label for="">' + errors + '</label>';
        errorHtml +=
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        $('body').append(errorHtml);
    }
});