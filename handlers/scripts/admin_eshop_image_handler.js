//---------------------------- UPLOAD IMAGE HANDLING-------------------------------------------------
const currentImage = document.querySelector("#product-image-preview")
const inputImage = document.querySelector('#product-image-file')

inputImage.onchange = () => {
    if (currentImage) currentImage.src = URL.createObjectURL(inputImage.files[0])
}

//---------------------------- DELETE IMAGE AND CHANGE TO DEFAULT IMAGE-------------------------------
const imageContainer = document.querySelector("#product-image-preview-container")
const imageOldUrl = document.querySelector("#product-image-old")

imageContainer.onclick = () => {
    const url = new URL("http://localhost/website/uploads/no-image-icon.png")
    if (currentImage) currentImage.src = url.pathname
    if (imageOldUrl) imageOldUrl.value = url.pathname
}

//---------------------------- TEXTAREA COUNTER FUCTIONALITY -------------------------------
const textareaLetterCounter = document.querySelector("#textarea-letter-counter")
const textareaProductDesription = document.querySelector("#product-description")
textareaProductDesription.oninput = () => {
    const lettersAmount = textareaProductDesription.value.length
    textareaLetterCounter.textContent = `${lettersAmount}/1000` 
}
