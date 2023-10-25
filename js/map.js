const Cells = document.querySelectorAll(".prison_cell");

var button1 = document.getElementById("btn-1");
var button2 = document.getElementById("btn-2");
var button3 = document.getElementById("btn-3");
var button4 = document.getElementById("btn-4");
var button5 = document.getElementById("btn-5");
var button6 = document.getElementById("btn-6");

var buttonsArray = [0, button1, button2, button3, button4, button5, button6];

const cellElements = document.querySelectorAll(".nr_celi");
const cellNumbers = [];

const cellButtons = {};

cellElements.forEach((element) => {
	const cellText = element.textContent.trim();
	const lastChar = cellText.charAt(cellText.length - 1);
	const cellNumber = parseInt(lastChar, 10);
	cellNumbers.push(cellNumber);

	const buttonSelector = `#btn-${cellNumber}`;
	cellButtons[cellNumber] = buttonSelector;
});

function loadPrisoners(){

	fetch("./display_cell_prisoners.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({ cellNumbers: cellNumbers }),
	})
		.then((response) => response.json())
		.then((data) => {
			console.log(data);
			let Cells=document.querySelectorAll(`.prison_cell`);
			Cells.forEach((cell)=>{
				cell.querySelector(".space_for_prisoners").textContent="";
			});
			// reszta kodu
			data.forEach((prisoner) => {
				const name = prisoner.name;
				const surname = prisoner.surname;
				const cellNumber = prisoner.cellNumber;
				const prisonerElement = document.createElement("span");
				prisonerElement.classList.add("prisoner");
				prisonerElement.textContent = `${name} ${surname}\n`;
				prisonerElement.style.whiteSpace = "pre";

				const ThisCell = document.querySelectorAll(
					`.prison_cell:has(button[id^="btn-${cellNumber}"])`
				);

				console.log("This cell: ", ThisCell);

				ThisCell.forEach((cell) => {
					let CellElement = cell.querySelector(".space_for_prisoners");
					CellElement.appendChild(prisonerElement);
				});
			});
			IsCellTaken();
			let maxHeight = 0;

			// Znalezienie największej wysokości
			elements.forEach((element) => {
				const elementHeight = element.offsetHeight;
				if (elementHeight > maxHeight) {
					maxHeight = elementHeight;
				}
			});
			console.log(maxHeight);
			// Ustawienie tej samej wysokości dla wszystkich elementów
			elements.forEach((element) => {
				element.style.height = `${maxHeight}px`;
			});
		})
		.catch((error) => {
			console.error("Błąd pobierania danych:", error);
		});
}

document.addEventListener("DOMContentLoaded", loadPrisoners());

function IsCellTaken() {
	const moveButton = document.querySelector(".move");
	Cells.forEach((item) => {
		console.log(Cells);

		const PrisonerDiv = item.querySelector(".space_for_prisoners");

		const spanPrisoner = PrisonerDiv.querySelector(".prisoner");
		console.log(spanPrisoner);

		if (!spanPrisoner || spanPrisoner.textContent.trim() == "") {
			item.style.backgroundColor = "#a3d7a3";
		} else {
			item.style.backgroundColor = "#fb8b8b";
			moveButton.classList.remove("d-none");
		}
	});
}

function openPopupAddPrisoner(clickedButton) {
	document.getElementById("popup").style.display = "block";
	var cell_number = buttonsArray.indexOf(clickedButton);
	localStorage.setItem("clickedButtonIndex", cell_number);
	sessionStorage.removeItem("prisonerAddedDisplayed");
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

	var popupContent = document.querySelector(".popup-content");

	popupContent.innerHTML = originalPopupContent;
	popupContent.style.display = "flex";
	popupContent.style.flexDirection = "column";

	document.getElementById("search_result").innerHTML = "";

	sessionStorage.removeItem("prisonerAddedDisplayed"); // Usuń zmienną po zamknięciu popupu
	document
		.getElementById("search_result")
		.addEventListener("click", handleSearchResultClick);
	loadPrisoners();
}

var originalPopupContent = document.querySelector(".popup-content").innerHTML;

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
						'<a href="#" class="list-group-item list-group-item-action disabled">Brak więźnia</a>';
				}
				html += "</div>";
				document.getElementById("search_result").innerHTML = html;
				document.getElementById("search_result1").innerHTML = html;
			}
		};
	} else {
		document.getElementById("search_result").innerHTML = "";
		document.getElementById("search_result1").innerHTML = "";
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
				document.querySelector(".popup-content").style.flexDirection = "row";

				document.getElementById("popup").style.display = "block";
				document.querySelector(".popup-content").innerHTML =
					'<h5 class="pb-3">Więzień został dodany do bazy.</h5><button type="button" class="btn-close" onclick="closePopup()"></button>';
				document.querySelector(".popup-content").style.display = "flex";
				document.querySelector(".popup-content").style.justifyContent =
					"space-between";
			} else {
				document.querySelector(".popup-content").style.flexDirection = "row";
				document.getElementById("popup").style.display = "block";
				document.querySelector(".popup-content").innerHTML =
					'<h5 class="pb-3">Nie można dodać więźnia do bazy.</h5><button type="button" class="btn-close" onclick="closePopup()"></button>';
				document.querySelector(".popup-content").style.display = "flex";
				document.querySelector(".popup-content").style.justifyContent =
					"space-between";
			}
		} else {
		}
	};

	xhr.send(formData);
}

function handleSearchResultClick(event) {
	const target = event.target;

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
		const searchBox1 = document.querySelector('input[name="search_box1"]');
		searchBox.value = targetName + " " + targetSurname + ", " + targetID;
		searchBox1.value = targetName + " " + targetSurname + ", " + targetID;

		// Wyczyść wyniki wyszukiwania
		document.getElementById("search_result").innerHTML = "";
		document.getElementById("search_result1").innerHTML = "";
	}
}

document
	.getElementById("search_result")
	.addEventListener("click", handleSearchResultClick);

document
	.getElementById("search_result1")
	.addEventListener("click", handleSearchResultClick);

const elements = document.querySelectorAll(".prison_cell");

// Inicjowanie zmiennej do przechowywania największej wysokości

function movePopup() {
	const Popup = document.querySelector(".move-popup");
	Popup.style.display = "block";
}
