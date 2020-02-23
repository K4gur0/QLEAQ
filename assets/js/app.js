/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// import bulmaCalendar from '~bulma-calendar/dist/js/bulma-calendar.min.js';

const fondEcran = require('../images/apart1.jpg');
const logo_blanc = require('../images/qleaq_blanc.png');
const logo_bleu = require('../images/qleaq_bleu.png');
const login_nomade = require('../images/svg/login_nomade.svg');
const login_proprio = require('../images/svg/login_proprio.svg');
const registration_nomade = require('../images/svg/registration_nomade.svg');
const registration_proprio = require('../images/svg/registration_proprio.svg');
const locataire = require('../images/svg/locataire.svg');
const vision = require('../images/svg/vision.svg');
const best_place = require('../images/svg/best_place.svg');
const mission = require('../images/svg/mission.svg');
const solution = require('../images/svg/solution.svg');
const offres = require('../images/svg/offres.svg');
const gestion_nomades = require('../images/svg/gestion_nomades.svg');
const gestion_proprios = require('../images/svg/gestion_proprios.svg');
const gestion_admins = require('../images/svg/gestion_admins.svg');


var html = "<img src='${fondEcran}'>";








// JS POUR CALANDAR //

// // Initialize all input of date type.
// const calendars = bulmaCalendar.attach('[type="date"]', options);
//
// // Loop on each calendar initialized
// calendars.forEach(calendar => {
//     // Add listener to date:selected event
//     calendar.on('date:selected', date => {
//         console.log(date);
//     });
// });
//
// // To access to bulmaCalendar instance of an element
// const element = document.querySelector('#my-element');
// if (element) {
//     // bulmaCalendar instance is available as element.bulmaCalendar
//     element.bulmaCalendar.on('select', datepicker => {
//         console.log(datepicker.data.value());
//     });
// }

 // FIN JS POUR CALANDAR  //




// {#    VERIFICATION REMPLISSAGE DES CHAMPS EN JAVASCRIPT   #}

// function verif_nombre(champ)
// {
//     var chiffres = new RegExp("[0-9]");
//     var verif;
//     var points = 0;
//
//     for(x = 0; x < champ.value.length; x++)
//     {
//         verif = chiffres.test(champ.value.charAt(x));
//         if(champ.value.charAt(x) == "."){points++;}
//         if(points > 1){verif = false; points = 1;}
//         if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
//     }
// }
//
// function verif_nombre_max(champ, max){
//
//     let x = champ.value;
//     let xsub = x.toString().substr(0,max);
//     champ.value = xsub;
//
// }