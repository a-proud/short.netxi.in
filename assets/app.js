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
        $('#short-url-result').removeClass('d-none');
        clickShortUrlPane();
        $('form[name="short_url"]').addClass('d-none');
    } else if (response.errors) {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').removeClass('d-block').addClass('d-none').html('');
        for (const [fieldName, errors] of Object.entries(response.errors)) {
            const $input = $form.find(`[name="short_url[${fieldName}]"]`);
            if ($input.length) {
                $input.addClass('is-invalid');
                const errorHtml = errors.map(e => `${e}`).join(' ');
                $input.closest('.input-wrapper').find('.invalid-feedback').removeClass('d-none').addClass('d-block').html(errorHtml);
            }
        }
    }
}

window.clickShortUrlPane = function() {
    const $pane = $('#short-url-result');
    if ($pane.length) {
        copyTextFromSelector('#short-url-result .short-link-text');
        $('.copy-icon')
            .removeClass('bi-clipboard')
            .addClass('bi-clipboard-check')
            .delay(1000)
            .queue(function(next) {
                $(this)
                    .removeClass('bi-clipboard-check')
                    .addClass('bi-clipboard');
                next();
            });
    }
};

$('body').on('click', '#short-url-result', function() {
    clickShortUrlPane();
});

window.copyTextFromSelector = function(selector) {
    const $el = $(selector);
    if ($el.length) {
        const text = $el.text();
        const tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
    }
};