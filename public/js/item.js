/**
 *  public/js/item.js
 *
 *  @authors Braedon Billingsley, Will Castillo, Noah Lanctot, Mehak Saini
 *  @copyright 2024
 *  @url https://bas-business-portfolio.greenriverdev.com
 **/
document.addEventListener("DOMContentLoaded", function() {
    window.onload = function () {
        fetch('get-items')
            .then(response => {
                return response.json();
            })
            .then(data => {

                const itemsContainer = document.querySelector('#items');

                for (let item of data) {
                    const element = document.createElement('div');

                    const titleElement = document.createElement('h2');
                    titleElement.textContent = item.title;

                    const descriptionElement = document.createElement('p');
                    descriptionElement.textContent = item.itemDescription;

                    const typeElement = document.createElement('p');
                    typeElement.textContent = item.itemType;

                    element.appendChild(titleElement);
                    element.appendChild(descriptionElement);
                    element.appendChild(typeElement);

                    itemsContainer.appendChild(element);
                }
            })
            .catch(error => console.error(error));
    };
});