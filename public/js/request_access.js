/**
 *  public/js/request_access.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('request-access-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'request-access', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                console.log(xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                handleResponse(response);
            } else {
                console.error('Server error: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Request error');
        };

        xhr.send(formData);
    });

    /**
     * Handles the response from an API request.
     *
     * @param {Object} response - The response object from the API request.
     * @property {string} response.error - The error message.
     * @property {string} response.success - The success message.
     * @return {void}
     */
    function handleResponse(response) {
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');

        if (response.error) {
            errorMessage.textContent = response.error;
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
        } else if (response.success) {
            successMessage.textContent = response.success;
            successMessage.style.display = 'block';
            errorMessage.style.display = 'none';
        }
    }
});