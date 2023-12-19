const addToCartForms = document.querySelectorAll('.action-cart');
addToCartForms.forEach((form) => form.addEventListener('submit', formSubmit))

async function formSubmit(e) {
    e.preventDefault();
    const productId = e.target.querySelector('input[name="product-id"]').value;
    const productAmount = e.target.querySelector('input[name="product-amount"]')?.value 
    const productTotalAmount = e.target.querySelector('input[name="product-total-amount"]')?.value;

    const body = {
        product_id: productId,
        amount: undefined ? null : productAmount,
        product_total_amount: undefined ? null : productTotalAmount,
    }
    
    try {
        const response = await fetch('handlers/scart_handle.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        })
        const responseObj = await response.json()
        setTimeout(() => location.reload(), 1000)
        messageBox(responseObj.message)
    } catch (err) {
        console.error(err);
    }
}

const deleteFromCartForms = document.querySelectorAll('.scart-product-delete')
deleteFromCartForms.forEach((form) => form.addEventListener('submit', deleteFormSubmit))

async function deleteFormSubmit(e){
    e.preventDefault()
    const productId = e.target.querySelector('input[name="product-id"]').value;

    const body = {
        product_id: productId,
    }

    try{
        const response = await fetch('handlers/scart_product_delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        })
        const responseObj = await response.json()
        // updateShoppingCart(responseObj.totalPieces)
        messageBox(responseObj.message)
        setTimeout(() => location.reload(), 1000)
    } catch (err) {
        console.error(err)
    }
}

function messageBox(message) {
    const body = document.querySelector('body');
    const div = document.createElement('div');
    div.setAttribute('class', 'messageBox');
    div.textContent = message;
    body.prepend(div);
    setTimeout(() => {
        document.body.removeChild(div);
    }, 1000);
}


function updateShoppingCart(totalPieces = 0) {
    if(!shoppingCartBubbleIcon.length) return
    if(totalPieces > 0) {
        shoppingCartBubbleIcon[0].style.display = "block"
    } else {
        shoppingCartBubbleIcon[0].style.display = "none"
    }
    shoppingCartBubbleIcon[0].textContent = totalPieces
}

// Add functionality for + and - buttons to increase pieces amount input field on Detail view page
const increment = document.querySelectorAll('.increment')
const decrement = document.querySelectorAll('.decrement')

if(increment.length && decrement.length) {
    increment.forEach((button) => button.addEventListener('click', (e) => updateCartInputValue(e, 1)))
    decrement.forEach((button) => button.addEventListener('click', (e) => updateCartInputValue(e, -1)))
}


// Update amount of pieces value in input field 
function updateCartInputValue(e, crementor) {
    const parent = e.target.closest('.action-cart')
    const cartInput = parent.querySelectorAll('.cart-input')
    let value = parseInt(cartInput[0].value)
    value += crementor
    if(value < 1) {
        value = 1
    }
    cartInput[0].value = parseInt(value)
}

function showMinimumAmountBox(input) {
    const div = document.createElement('div')
    div.className = 'minimum-amount-box'
    div.textContent = "Minimalne mnozstvo je 1 kus"
}

// Redirect to the shopping cart page Eventlistener
const shoppingCartImage = document.querySelectorAll('.shopping-cart-image')
shoppingCartImage.forEach((image) => image.addEventListener('click', () => location.href = "scart_list.php"))


// Change STOCKED color product if it is available or NOT
const stocked = document.querySelectorAll('.stocked')
stocked.forEach(div => {
    if (div.dataset.stocked === 'Skladom')  {
        div.style.color = 'green'
    } else {
        div.style.color = 'red'
    }
})

// Created self submit form eventlistener when input value is changed, new Event was used to handle the change
addToCartForms.forEach((form) => {form.addEventListener('custom:selfSubmitEvent', (e) => {formSubmit(e); setTimeout(() => location.reload(), 1000)})})

// Add functionality for + and - buttons to increase total pieces input field on Shopping cart view page
const selfSubmitEvent = new Event('custom:selfSubmitEvent')

const increase = document.querySelectorAll('.increase')
increase.forEach((button) => button.addEventListener('click', (e) => {
    e.target.closest('.action-cart').dispatchEvent(selfSubmitEvent)
}))

const decrease = document.querySelectorAll('.decrease')
decrease.forEach((button) => button.addEventListener('click', (e) => {
    e.target.closest('.action-cart').dispatchEvent(selfSubmitEvent)
}))

const inputRadioShipment = document.querySelectorAll('input[name=shipment]')
const inputRadioPayment = document.querySelectorAll('input[name=payment]')
if(inputRadioShipment.length && inputRadioPayment.length) {
    inputRadioShipment[0].checked = true
    inputRadioPayment[0].checked = true
}




