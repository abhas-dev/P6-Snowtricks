const axios = require("axios");

const loadMoreBtn = document.querySelector('#js-loadMore_tricks');
const tricksBlock = document.querySelector('#tricks');
const loadMoreSection = document.querySelector('#loadMoreSection');
let nextPage = 2;

async function  getMoreTricks(e){
    e.preventDefault();
    const url = this.getAttribute('href');
    loadMoreBtn.textContent = 'Chargement...';
    await axios({
        method: 'post',
        url: `${url}?page=${nextPage}`,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        data: {
            lastTrick: this.dataset.lastTrick
        }
    })
        .then(response => response.data)
        .then(data => {
            tricksBlock.insertAdjacentHTML('beforeend', data._template.content);
            nextPage = data.nextPage;
            !data.nextPage ? loadMoreSection.innerHTML = "Il n'y a pas d'autre trick" : loadMoreBtn.textContent = 'Charger Plus';
        })
        .catch(e => {
            console.log(e);
        });
}

loadMoreBtn.addEventListener('click', getMoreTricks);

