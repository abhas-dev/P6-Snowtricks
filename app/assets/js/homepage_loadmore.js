const axios = require("axios");

const loadMoreBtn = document.querySelector('#js-loadMore_tricks');

async function  getMoreTricks(e){
    e.preventDefault();
    const url = this.getAttribute('href');
    await axios({
        method: 'post',
        url: url,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        data: {lastTrick: this.dataset.lastTrick}
    })
        .then(response => {
            console.log(response);
        })
        .catch(e => {
            console.log(e);
        })
    // axios.post(url, {
    //     headers: { "X-Requested-With": "XMLHttpRequest" },
    //     // On recupere le token dans le dataset l'attribut token
    //     // On recupere l'index
    //     data: { lastTrick: this.dataset.lastTrick },
    // // })

}

loadMoreBtn.addEventListener('click', getMoreTricks);

