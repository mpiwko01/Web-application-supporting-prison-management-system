const Cells = document.querySelectorAll(".prison_cell");

var button1 = document.getElementById("btn-1");
var button2 = document.getElementById("btn-2");
var button3 = document.getElementById("btn-3");
var button4 = document.getElementById("btn-4");
var button5 = document.getElementById("btn-5");
var button6 = document.getElementById("btn-6");

var buttonsArray = [0, button1, button2, button3, button4, button5, button6];

const IsCellTaken = () => {
	Cells.forEach((item) => {
		const PrisonerSpan = item.querySelector(".prisoner");

		if (PrisonerSpan && PrisonerSpan.textContent.trim() == "") {
			item.style.backgroundColor = "#a3d7a3";
		} else {
			item.style.background = "red";
		}
	});
};

function openPopupAddPrisoner(clickedButton) {
	document.getElementById("popup").style.display = "block";
	var cell_number = buttonsArray.indexOf(clickedButton);
	localStorage.setItem("clickedButtonIndex", cell_number);
}

function handleClick(event) {
	document.getElementById("popup").style.display = "block";
	var clickedButton = event.target;
	var cell_number = buttonsArray.indexOf(clickedButton);
	localStorage.setItem("cell", cell_number);
	var cell = localStorage.getItem("cell");
	console.log(cell);
}

var buttons = document.querySelectorAll(".btn-add");
buttons.forEach(function (button) {
	button.addEventListener("click", handleClick);
});

function closePopup() {
	document.getElementById("popup").style.display = "none";
}

IsCellTaken();

function load_data(query) {
	if (query.length > 2) {
		var form_data = new FormData();

		form_data.append("query", query);

		var ajax_request = new XMLHttpRequest();

		ajax_request.open("POST", "process_data.php");

		ajax_request.send(form_data);

		ajax_request.onreadystatechange = function () {
			if (ajax_request.readyState == 4 && ajax_request.status == 200) {
				var response = JSON.parse(ajax_request.responseText);

				var html = '<div class="list-group">';

				if (response.length > 0) {
					for (var count = 0; count < response.length; count++) {
						html +=
							'<input type="submit" name="prisoner_add" value="' +
							response[count].name +
							" " +
							response[count].surname +
							" " +
							response[count].prisoner_id +
							'">' +
							'<input type="hidden"  name="prisoner_add_id" value="' +
							response[count].prisoner_id +
							'">' +
							`<input type="hidden"  name="prisoner_add_cell_number" value="${localStorage.getItem(
								"cell"
							)}">`;
					}
				} else {
					html +=
						'<a href="#" class="list-group-item list-group-item-action disabled">No Data Found</a>';
				}

				html += "</div>";

				document.getElementById("search_result").innerHTML = html;
			}
		};
	} else {
		document.getElementById("search_result").innerHTML = "";
	}
}

function closeCom() {
	document.querySelector("#com1").style.display = "none";

	fetch("remove_password_change_try.php");
}

function addPrisoner() {
	// Pobierz dane z formularza
	var searchValue = document.querySelector('input[name="search_box"]').value;
	var selectedDate = document.querySelector('input[name="start_date"]').value;

	var selectedCell = localStorage.getItem("cell");
	console.log(selectedCell);

	// Wysyłanie danych na serwer
	var formData = new FormData();
	formData.append("search", searchValue);
	formData.append("date", selectedDate);
	formData.append("cell", selectedCell);

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "add_prisoner.php", true);

	xhr.onload = function () {
		if (xhr.status >= 200 && xhr.status < 300) {
			// Obsługa sukcesu
			var response = xhr.responseText;
			console.log(response);
			//console.log(response);

			if (response === "success") {
				// Ustaw informację o sukcesie w popupie
				document.getElementById("popup").style.display = "block";
				document.querySelector(".popup-content").innerHTML =
					'<h5 class="pb-3">Więzień został dodany do bazy.</h5><button type="button" class="btn-close" onclick="closePopup()"></button>';
				document.querySelector(".popup-content").style.display = "flex";
				document.querySelector(".popup-content").style.justifyContent =
					"space-between";
			} else {
				document.getElementById("popup").style.display = "block";
				document.querySelector(".popup-content").innerHTML =
					'<h5 class="pb-3">Nie można dodać więźnia do bazy.</h5><button type="button" class="btn-close" onclick="closePopup()"></button>';
				document.querySelector(".popup-content").style.display = "flex";
				document.querySelector(".popup-content").style.justifyContent =
					"space-between";
			}
			// Tutaj możesz obsłużyć odpowiedź od serwera, np. wyświetlić komunikat użytkownikowi.
			// Możesz także zaktualizować mapę lub inne elementy na stronie.
		} else {
			// Obsługa błędu
			//console.error('Błąd podczas wysyłania danych.');
		}
	};

	//console.log(formData);

	xhr.send(formData);
}

document
	.getElementById("search_result")
	.addEventListener("click", function (event) {
		const target = event.target;

		//Sprawdź, czy kliknięto na sugestię (element input z atrybutem name="prisoner_add")
		if (target.name === "prisoner_add") {
			// Pobierz wartość klikniętej sugestii
			const suggestionValue = target.value;
			console.log(suggestionValue);

			const targetName = suggestionValue.split(" ")[0];
			const targetSurname = suggestionValue.split(" ")[1];
			const targetID = suggestionValue.split(" ")[2];

			console.log(targetID);

			// Zaktualizuj pole wprowadzania wybraną sugestią
			const searchBox = document.querySelector('input[name="search_box"]');
			searchBox.value = targetName + " " + targetSurname + ", " + targetID;

			// Wyczyść wyniki wyszukiwania
			document.getElementById("search_result").innerHTML = "";
		}
	});
