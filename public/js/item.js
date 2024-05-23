document.addEventListener("DOMContentLoaded", function() {
    const selectItemTypeInput = document.querySelector("#itemType");

    fetch('/get-items')
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(items => {
            items.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.itemType;  // Use Item's `getItemType` method
                option.text = item.itemType;   // You might want to display something different here
                selectItemTypeInput.add(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Failed to fetch item types. Please reload the page.");
        });
});