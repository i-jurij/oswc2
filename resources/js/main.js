import '../css/main.css';
import '../images/favicon.png';

// import src from "./avatar.png";
// const Profile = () => <img src={src} />;

// import naja from 'naja';
import netteForms from 'nette-forms';
window.Nette = netteForms;
// document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));
netteForms.initOnLoad();