// import img from '.. /img/simao.jpg';
// document.body.style.background = `url(${img}) `

// import naja from 'naja';
import netteForms from 'nette-forms';
window.Nette = netteForms;
// document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
netteForms.initOnLoad();

function closeFlash() {
    let fbci = document.querySelector('#for_button_close_insert')
    if (fbci) {
        fbci.innerHTML = '<button id="close_flash_message">Close</button>';
        close_flash_message.onclick = function () {
            flash_message.remove();
        };
    }
}
document.addEventListener('DOMContentLoaded', closeFlash());