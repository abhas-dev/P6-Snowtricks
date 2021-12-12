const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');
    item.className= "my-2";

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
    const removeFormButton = document.createElement('button');
    removeFormButton.classList = "btn btn-danger";
    removeFormButton.innerText = 'Supprimer cette image';
    tagFormLi.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        tagFormLi.remove();
    });
};

document
    .querySelectorAll('.add_item_link')
    .forEach(btn => btn.addEventListener("click", addFormToCollection));
