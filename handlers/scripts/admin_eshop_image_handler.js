//---------------------------- UPLOAD IMAGE HANDLING-------------------------------------------------
const currentImage = document.querySelector("#product-image-preview")
const inputImage = document.querySelector('#product-image-file')

inputImage.onchange = () => {
    currentImage.src = URL.createObjectURL(inputImage.files[0])
}

const imageContainer = document.querySelector("#product-image-preview-container")
const imageOldUrl = document.querySelector("#product-image-old")

imageContainer.onclick = () => {
    const url = new URL("http://localhost/website/uploads/no-image-icon.png")
    currentImage.src = url.pathname
    imageOldUrl.value = url.pathname
}

const textareaLetterCounter = document.querySelector("#textarea-letter-counter")