let voltModal = document.querySelector('div.modal_volts')
let btnTrigger = document.querySelector('button.trigger_modal')
let btnClose = document.querySelector('img.btn_close')

voltModal.classList.remove('d_none')

modalVoltsOpen = (e) => {
    e.preventDefault()
    voltModal.classList.add('opened')
}

modalVoltsClose = () => {
    voltModal.classList.toggle('opened')
}

btnTrigger.addEventListener("click", (e) => {
    modalVoltsOpen(e)
})

btnClose.addEventListener("click", () => {
    modalVoltsClose()
})

window.onclick = function(e) {
    if (e.target == voltModal) {
        modalVoltsClose()
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        modalVoltsClose()
    }
})
