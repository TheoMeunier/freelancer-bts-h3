/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/style.scss'

// import boostrap
import '@popperjs/core';
import 'bootstrap';

// start the Stimulus application
import './bootstrap'
import TomSelect from "tom-select";

const btn_contact_prestation = document.querySelector('#btn_contact_prestation')
const form_contact_prestation = document.querySelector('#form_contact_prestation')

if (btn_contact_prestation && form_contact_prestation) {
    btn_contact_prestation.addEventListener('click', (e) => {
        e.preventDefault()

        if (form_contact_prestation.classList.contains('d-none')) {
            form_contact_prestation.classList.remove('d-none')
            form_contact_prestation.classList.add('d-block')
        } else {
            form_contact_prestation.classList.remove('d-block')
            form_contact_prestation.classList.add('d-none')
        }
    })
}

// Tom select
const prestation = document.querySelector('#prestation_categories')
const categories = document.querySelector('#categories')

new TomSelect(prestation, {
    plugins: {
        remove_button: {title: 'Supprimer'},
    },
})

new TomSelect(categories, {
    plugins: {
        remove_button: {title: 'Supprimer'},
    },
})


