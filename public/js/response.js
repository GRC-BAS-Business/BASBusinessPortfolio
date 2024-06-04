/**
 *  public/js/response.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
document.addEventListener("DOMContentLoaded", function() {
    function handleFormSubmit(e) {
        e.preventDefault();
        const form = e.target;

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {'Accept': 'application/json'}
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('error-message').innerText = data.error;
                    document.getElementById('error-message').style.display = 'block';
                } else if (data.success) {
                    document.getElementById('success-message').innerText = data.success;
                    document.getElementById('success-message').style.display = 'block';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            })
            .catch((error) => console.error('Error: ', error));
    }

    const forms = ['login-form', 'register-form', 'request-access-form', 'access-code-form', 'item-form'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
    });
});