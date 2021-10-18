/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

let container = document.querySelectorAll('.js-vote-arrows')

container.forEach(function (elementDiv) {

    elementDiv.querySelectorAll('button').forEach(function (elementLink) {
        elementLink.addEventListener('click', function (e) {
            e.preventDefault();
            fetch(
                "/comments/10/vote/" + elementLink.dataset.direction, {method: 'POST'}
            ).then((response) => response.json())
                .then((data) => {elementDiv.querySelector('.js-vote-total').textContent = data.votes})
                .catch(error => console.log('Un problème est survenu ' + error.message))
        })
    })
})
