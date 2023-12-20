//---------------------------- UPLOAD IMAGE HANDLING-------------------------------------------------
const currentImage = document.querySelector("#product-image-preview")
const inputImage = document.querySelector('#product-image-file')

inputImage.onchange = () => {
    currentImage.src = URL.createObjectURL(inputImage.files[0])
}