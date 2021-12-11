const axios = require("axios");


/********************Delete Images********************/
let links = document.querySelectorAll("[data-delete]");

function onClickDeleteBtn(e) {
  e.preventDefault();
  // const url = this.href;
  const url = this.getAttribute("href");
  let id = url.match(/image-delete\/(.+)/)[1];

  if (confirm("Voulez-vous vraiment supprimer cette image?")) {
    axios
      .delete(this.getAttribute("href"), {
        headers: { "X-Requested-With": "XMLHttpRequest" },
        // On recupere le token dans le dataset l'attribut token
        data: { _token: this.dataset.token },
      })
      .then((response) => response.data)
      .then((data) => {
        let trickImageDiv = document.getElementById("trickImage-" + id);
        data.code === 200
          ? trickImageDiv.parentNode.removeChild(trickImageDiv)
          : alert(data.message);
        // data.code === 200 ? this.parentElement.parentElement.remove() :
        // alert(data.message)
      })
      .catch((error) => {
        console.log(error);
      });
  }
}

links.forEach((link) => {
  link.addEventListener("click", onClickDeleteBtn);
});

/********************Set Main Picture********************/
const radios = document.querySelectorAll(".js-mainPicture");

async function onSelectMainPicture(e) {
  e.preventDefault();
  try {
    const response = await axios.post(this.dataset.mainpicture);
  } catch (error) {
    console.log(error);
  }
}

radios.forEach((radio) =>
  radio.addEventListener("change", onSelectMainPicture)
);


/********************Change div content********************/
const tabs = document.querySelectorAll('.js-trickEditTab');

async function showEdit(e){
  e.preventDefault();
  try {
    fetch(this.href, {
      method: "POST",
      body: this.dataset.content,
      headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
        .then(response => response.json())
        .then(data => {
            document.querySelector(".js-content").innerHTML = data._template.content;
            let deleteVideoLinks = document.querySelectorAll("[data-delete]");
            deleteVideoLinks.forEach(link => link.addEventListener('click', onClickDeleteVideo));
        });

  }
  catch (error) {
    console.log(error);
  }
}
tabs.forEach(tab => tab.addEventListener('click', showEdit));

/********************Delete Video********************/

async function onClickDeleteVideo(e)
{
    const url = this.dataset.link;
    let id = url.match(/video-delete\/(.+)/)[1];

    if (confirm("Voulez-vous vraiment supprimer cette video?")) {
        axios
            .delete(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
                data: { _token: this.dataset.token },
            })
            .then((response) => response.data)
            .then((data) => {
                let trickVideoDiv = document.querySelector(".trickVideo-" + data.trickVideoId);
                data.code === 200
                    // ? console.log(trickVideoDiv)
                    ? trickVideoDiv.remove()
                    : alert(data.message);
            })
            .catch((error) => {
                console.log(error);
            });
    }
}