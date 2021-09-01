let voltModal = document.querySelector('div.modal_volts')
let btnTrigger = document.querySelector('button.trigger_modal')
let btnClose = document.querySelector('img.btn_close')

voltModal.classList.remove('d_none')

modalVoltsOpen = (e) => {
    e.preventDefault()

    voltModal.classList.toggle('opened')
}

btnTrigger.addEventListener("click", (e) => {
    modalVoltsOpen(e)
})

btnClose.addEventListener("click", (e) => {
    modalVoltsOpen(e)
})

window.onclick = function(e) {
    if (e.target == voltModal) {
        modalVoltsOpen(e)
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        modalVoltsOpen(e)
    }
})
