/**
 *  public/js/access_code.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
document.addEventListener("DOMContentLoaded", function() {

    // handle form submission
    document.getElementById('access-code-form').addEventListener('submit', (e) => {
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
                if(data.error){
                    document.getElementById('error-message').innerText = data.error;
                    document.getElementById('error-message').style.display = 'block';
                }else if(data.redirect){
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            })
            .catch((error) => console.error('Error: ', error));
    });
});