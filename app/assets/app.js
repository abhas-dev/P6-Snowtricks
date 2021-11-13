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

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index ++;

    addImageFormDeleteLink(item);
};

const addImageFormDeleteLink = (tagFormLi) => {
    const removeFormButton = document.createElement('button')
    removeFormButton.classList = "btn btn-danger"
    removeFormButton.innerText = 'Supprimer cette image'
    tagFormLi.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault()
        tagFormLi.remove();
    });
}

document
    .querySelectorAll('.add_item_link')
    .forEach(btn => btn.addEventListener("click", addFormToCollection));
