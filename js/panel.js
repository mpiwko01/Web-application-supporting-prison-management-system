const imageHolder = document.querySelector(".image-holder");

const URL = "https://randomuser.me/api/";

fetch(URL)
	.then((res) => res.json())
	.then((data) =>
		imageHolder.setAttribute("src", data.results[0].picture.medium)
	)
	.catch((err) => console.log(err));

	function openTab(clickedButton) {
		console.log("Kliknięto przycisk1");
		const tab_personal_info = document.querySelector(".personal-info");
		const tab_raports = document.querySelector(".raports");
		const tab_logs = document.querySelector(".logs");
		const tab_settings = document.querySelector(".settings");

		console.log("Kliknięto przycisk2");

		tab_personal_info.classList.add("d-none");
		tab_raports.classList.add("d-none");
		tab_logs.classList.add("d-none");
		tab_settings.classList.add("d-none");


		if (clickedButton.classList.contains("btn-1")) { //informacje
			tab_personal_info.classList.remove("d-none");   
		} else if (clickedButton.classList.contains("btn-2")) { //raporty
			tab_raports.classList.remove("d-none");
			console.log("Kliknięto przycisk4");
		} else if (clickedButton.classList.contains("btn-3")) { //logowanie
			tab_logs.classList.remove("d-none");
		} else if (clickedButton.classList.contains("btn-4")) { //ustawienia
			tab_settings.classList.remove("d-none");
		}
	}


	

