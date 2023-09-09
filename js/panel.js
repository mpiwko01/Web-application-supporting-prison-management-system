const imageHolder = document.querySelector(".image-holder");

const URL = "https://randomuser.me/api/";

fetch(URL)
	.then((res) => res.json())
	.then((data) =>
		imageHolder.setAttribute("src", data.results[0].picture.medium)
	)
	.catch((err) => console.log(err));
