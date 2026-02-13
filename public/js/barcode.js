function printBarcode() {
    var printContents = document.querySelector('.barcode-container').innerHTML;
    var originalContents = document.body.innerHTML;
    var productNameElement = document.querySelector('.card-title');
    var productName = productNameElement ? productNameElement.innerText : 'Producto';

    document.body.innerHTML = `
        <div style="text-align: center; padding-top: 50px;">
            <h2>${productName}</h2>
            ${printContents}
        </div>
    `;

    window.print();

    document.body.innerHTML = originalContents;
    location.reload(); 
}
