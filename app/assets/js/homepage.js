const axios = require("axios");

const loadMoreBtn = document.querySelector('#js-loadMore_tricks');
const tricksBlock = document.querySelector('#tricks');
const loadMoreSection = document.querySelector('#loadMoreSection');
let nextPage = 2;

async function onRemoveTrick(e) {
  e.preventDefault();
  try {
    const url = this.getAttribute('href');
    let slug = url.match(/trick\/(.+)\/delete/)[1];

    const response =
        await axios
            .delete(url, {
              headers : {'X-Requested-With' : 'XMLHttpRequest'},
              data : {"_token" : this.dataset.token}
            })
            .then(response => response.data)
            .then(data => {
              let trickDiv = document.getElementById("trick-" + slug);
              data.code === 200 ? trickDiv.parentNode.removeChild(trickDiv)
                                : alert(data.message)
            })

  } catch (e) {
    console.log(e);
  }
}

function bindRemoveTrick() {
  let removeTrickBtn = document.querySelectorAll('.js-deleteTrick');

  removeTrickBtn.forEach(removeBtn => {
    removeBtn.removeEventListener('click', onRemoveTrick);
    removeBtn.addEventListener('click', onRemoveTrick);
  });
}

bindRemoveTrick();

async function getMoreTricks(e) {
  e.preventDefault();
  const url = this.getAttribute('href');
  loadMoreBtn.textContent = 'Chargement...';
  await axios({
    method : 'post',
    url : `${url}?page=${nextPage}`,
    headers : {
      "X-Requested-With" : "XMLHttpRequest",
      "Content-Type" : "application/json"
    },
    data : {lastTrick : this.dataset.lastTrick}
  })
      .then(response => response.data)
      .then(data => {
        tricksBlock.insertAdjacentHTML('beforeend', data._template.content);
        nextPage = data.nextPage;
        !data.nextPage
            ? loadMoreSection.innerHTML = "Il n'y a pas d'autre trick"
            : loadMoreBtn.textContent = 'Charger Plus';
        bindRemoveTrick();
      })
      .catch(e => { console.log(e); });
}

loadMoreBtn.addEventListener('click', getMoreTricks);
