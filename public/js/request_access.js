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