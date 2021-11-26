const axios = require("axios");

// Change div content
function showOutput(res) {
  document.querySelector("#js-content").innerHTML = ``;
}

let links = document.querySelectorAll("[data-delete]");
// Delete Image
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

// Set Main Picture
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
