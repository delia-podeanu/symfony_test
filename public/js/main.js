
const articles = document.querySelector("#articles");

if (articles) {
  articles.addEventListener("click", (e) => {
    console.log(e.target.className);
    if (
      e.target.className === "btn btn-danger delete-btn" ||
      e.target.className === "far fa-trash-alt"
    ) {
      const id = e.target.getAttribute("data-id");

      fetch(`/article/delete/${id}`, {
        method: "DELETE",
      }).then((res) => window.location.reload());
    }
  });
}


const comments = document.querySelector("#comments");

if (comments) {
  comments.addEventListener("click", (e) => {
    console.log(e.target.className);
    if (
      e.target.className === "btn btn-danger delete-comment" ||
      e.target.className === "far fa-trash-alt delete-comment"
    ) {
      const id = e.target.getAttribute("data-id");

      fetch(`/comment/delete/${id}`, {
        method: "DELETE",
      }).then((res) => window.location.reload());
    }
  });
}