// import img from '.. /img/simao.jpg';
// document.body.style.background = `url(${img}) `

// import naja from 'naja';
import netteForms from 'nette-forms';
window.Nette = netteForms;
// document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
netteForms.initOnLoad();

/// function for deleting flash message from DOM ///
function delFlash(delay_in_seconds) {
    setTimeout(function () {
        let flash = document.querySelector('.flash');
        if (!!flash) {
            flash.remove();
            document.removeEventListener('DOMContentLoaded', delFlash());
        }
    }, delay_in_seconds * 1000);
}
// adding delFlash to pages
document.addEventListener('DOMContentLoaded', delFlash(5));