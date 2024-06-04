/**
 *  public/js/request_access.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
document.addEventListener("DOMContentLoaded", function() {
    // Form to request access
    const requestAccessForm = document.getElementById('request-access-form');
    if (requestAccessForm) {
        requestAccessForm.addEventListener('submit', function(event) {
            event.preventDefault();
            handleSubmit(event, 'request-access', 'POST');
        });
    }

    // Function to handle form submissions
    function handleSubmit(event, url, method) {
        event.preventDefault();
        let errorElement = document.getElementById('error-message');
        let successElement = document.getElementById('success-message');

        const form = event.target;
        const formData = new FormData(form);

        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: method === 'POST' ? formData : undefined
        })
            .then(response => response.json())  // Convert the response to JSON
            .then(data => {
                // Here you handle the response
                if(data.status === 'error') {
                    // Show the error message
                    errorElement.textContent = data.message;
                    errorElement.style.display = 'block';
                }
                else {
                    // Show the success message
                    successElement.textContent = data.message;
                    successElement.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }
});