function vlookupProduct(){
    $.ajax({
        url: '/product/GET/barcode:' + barcode,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Process the Ajax response
            // ...
        }
    });
}

//TODO: Add a function to handle the response from the server and load the products into the table