function searchOrder() {
    const orderNumber = document.getElementById('orderNumberInput').value;

    fetch('/delivery/test/${orderNumber}')
        .then(response => response.json())
        .then(data => {
            // Handle the response data, e.g., update the page content
            console.log(data);
        })
        .catch(error => {
            // Handle any errors that occur during the AJAX request
            console.error(error);
        });
}