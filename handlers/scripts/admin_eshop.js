
// --------------------------CONFIRMATION ALERT BEFORE DELETE PRODUCT------------------------------
const parent = document.querySelectorAll('.product-delete-button-tableData')
parent.forEach(td => td.querySelector('form').addEventListener('submit', deleteProduct));

function deleteProduct(e) {
    e.preventDefault();
    let text = "Naozaj chcete vymazať produkt z databázy?"
    if (confirm(text) == true) {
        e.target.submit()
    } else {
        console.log('Product delete cancel')
    }
}

//---------------------------- UPLOAD IMAGE HANDLING-------------------------------------------------
const currentImage = document.querySelector("#product-image-preview")
const inputImage = document.querySelector('#product-image-file')

inputImage.onchange = () => {console.log('Image changed')
    currentImage.src = URL.createObjectURL(inputImage.files[0])
}