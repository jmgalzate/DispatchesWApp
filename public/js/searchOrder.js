function searchOrder() {
    const orderNumber = document.getElementById('orderNumberInput').value;

    fetch('/delivery/test/' + orderNumber)
        .then(response => response.text()) // Change response.json() to response.text()
        .then(data => {
            // Handle the HTML content returned from the controller
            // For example, you can inject it into a div element with the ID 'result'
            document.getElementById('result').innerHTML = data;
        })
        .catch(error => {
            // Handle any errors that occur during the AJAX request
            console.error(error);
        });
}
