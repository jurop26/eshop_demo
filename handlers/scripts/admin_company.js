const editButton = document.querySelectorAll('.company-input-container input[type="submit"]')
editButton.forEach(button => button.addEventListener('click', e => {
    e.preventDefault();

    if (e.target.value === 'Save') {
        formSubmit(e.target.closest('form'))
    }

    e.target.value = e.target.value === 'Edit' ? 'Save' : 'Edit'
    e.target.closest('form').querySelectorAll('input[type="text"]').forEach(input => {
        input.disabled = !input.disabled
    })

}))

async function formSubmit(e) {
    // e.preventDefault();
    let body = {}
    const inputs = e.querySelectorAll('input[type="text"]');
    inputs.forEach(input => {
        let name
        const inputName = input.name.split('-')
        if(inputName.length > 2) {name = `${inputName[0]}_${inputName[1]}_${inputName[2]}`}
        else {name = `${inputName[0]}_${inputName[1]}`}
        body = {...body, [name]: input.value}
    })
  
    try {
        const response = await fetch('handlers/company_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        })
        const responseObj = await response.json()
        // setTimeout(() => location.reload(), 1000)
        // messageBox(responseObj.message)
        console.log(responseObj.message)
    } catch (err) {
        console.error(err);
    }
}