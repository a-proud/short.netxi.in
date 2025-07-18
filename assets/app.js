/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap';
import './styles/app.scss';

// Импорт jQuery и делаем его глобальным
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

$(function () {
    $('form.responseformat').on('submit', function (e) {
        const $form = $(this);
        const responseFormatClass = $form.attr('class').split(' ').find(c => c.startsWith('responseformat-'));

        if (responseFormatClass && responseFormatClass.endsWith('-json')) {
            e.preventDefault();

            const handlerName = $form.data('submithandler');
            const formData = new FormData(this);

            $.ajax({
                url: $form.attr('action') || window.location.href,
                method: $form.attr('method') || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function (response) {
                    if (handlerName && typeof window[handlerName] === 'function') {
                        window[handlerName](response, $form);
                    } else {
                        console.warn('Handler', handlerName, 'not found');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Form submission error:', error);
                }
            });
        }
    });

    
});

window.shortUrlFormSubmit = function (response, $form)
{
    if (response.shortUrl) {
        $('#short-url-result .short-link-text').html(response.shortUrl);
    }
}