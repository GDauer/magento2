function translateVat() {
    let getElements = document.querySelectorAll('address.translate')

    for (let index = 0; index < getElements.length; index++) {
        
        let allWords = getElements[index].innerHTML.indexOf("VAT")
        
        if (allWords != -1) {
            let likeString = getElements[index].innerHTML
            let translated = likeString.replace(/VAT/g, 'CPF')

            getElements[index].innerHTML = translated
        }
        
    }
}

document.addEventListener("DOMContentLoaded", translateVat)