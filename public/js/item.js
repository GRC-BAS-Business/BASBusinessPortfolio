/**
 *  public/js/item.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
// public/js/item.js
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('item-form');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        const data = {
            title: formData.get('title'),
            itemDescription: formData.get('itemDescription'),
            itemType: formData.get('itemType')
        };

        fetch('create-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    successMessage.style.display = 'block';
                    errorMessage.style.display = 'none';
                    successMessage.textContent = 'Item created successfully!';
                    form.reset();
                } else {
                    successMessage.style.display = 'none';
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = 'Error creating item: ' + result.message;
                }
            })
            .catch(error => {
                successMessage.style.display = 'none';
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'Error creating item: ' + error.message;
            });
    });
});