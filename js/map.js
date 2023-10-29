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

function loadPrisoners() {
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
			let Cells = document.querySelectorAll(`.prison_cell`);
			Cells.forEach((cell) => {
				cell.querySelector(".space_for_prisoners").textContent = "";
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

				//console.log("This cell: ", ThisCell);

				ThisCell.forEach((cell) => {
					let CellElement = cell.querySelector(".space_for_prisoners");
					CellElement.appendChild(prisonerElement);
				});
			});
			IsCellTaken();
			let maxHeight = 0;
			const elements = document.querySelectorAll(".prison_cell");
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
		let currentCellPrisoners = 0; //zmienna zbierająca liczbę więźniów w obecnej celi
		console.log("item: ", item);
		const prisonerDiv = item.querySelector(".space_for_prisoners");
		const List = item.querySelector(".list_of");
		const spanPrisoner = prisonerDiv.querySelectorAll(".prisoner");
		spanPrisoner.forEach((prisoner) => {
			currentCellPrisoners += 1; // Dodaje do zmiennej w pętli 1 za każdego więźnia
		});
		if (currentCellPrisoners == 0) {
			// Sprawdzam liczbę więźniów w aktualnej celi
			item.style.backgroundColor = "#a3d7a3"; // Brak więźniów w celi to kolor zielony
			List.textContent = "PUSTA CELA"; //Zmieniam wyświetlany tekst na "PUSTA CELA"
		} else if (currentCellPrisoners > 0 && currentCellPrisoners < 4) {
			item.style.backgroundColor = "#ffbd23"; // Jeśli są więźniowie, ale jest jeszcze miejsce to kolor celi jest pomarańczowy
			moveButton.classList.remove("d-none");
		} else {
			item.style.backgroundColor = "#fb8b8b"; //Jeśli jest osiągnięty limit miejsc to kolor celi jest czerwony
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
	document.getElementById("popup1").style.display = "none";

	var popupContent = document.querySelector(".popup-content");
	var popupContent1 = document.querySelector(".popup-content1");

	popupContent.innerHTML = originalPopupContent;
	popupContent.style.display = "flex";
	popupContent.style.flexDirection = "column";
	popupContent1.innerHTML = originalPopupContent;
	popupContent1.style.display = "flex";
	popupContent1.style.flexDirection = "column";

	document.getElementById("search_result").innerHTML = "";

	sessionStorage.removeItem("prisonerAddedDisplayed"); // Usuń zmienną po zamknięciu popupu
	document
		.getElementById("search_result")
		.addEventListener("click", handleSearchResultClick);
	loadPrisoners();
}

function closeMovePopup() {
	const movePopup = document.querySelector(".move-popup");
	movePopup.style.display = "none";
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
			}
		};
	} else {
		document.getElementById("search_result").innerHTML = "";
	}
}

function showMessage(place, id, message) {
	//funkcja, wyświetlająca komunikaty przy próbie dodania/przeniesienia więźnia
	document.querySelector(place).style.flexDirection = "row";
	document.getElementById(id).style.display = "block";
	document.querySelector(place).innerHTML =
		'<h5 class="pb-3">' +
		message +
		'</h5><button type="button" class="btn-close" onclick="closePopup()"></button>';
	document.querySelector(place).style.display = "flex";
	document.querySelector(place).style.justifyContent = "space-between";
}

function addPrisoner() {
	// Pobierz dane z formularza
	var searchValue = document.querySelector('input[name="search_box"]').value;
	var selectedDate = document.querySelector('input[name="start_date"]').value;

	var selectedCell = localStorage.getItem("cell");
	//console.log(selectedCell);

	// Wysyłanie danych na serwer
	var formData = new FormData();
	formData.append("search", searchValue);
	formData.append("date", selectedDate);
	formData.append("cell", selectedCell);

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "add_prisoner.php", true);

	xhr.onload = function () {
		if (xhr.status >= 200 && xhr.status < 300) {
			var response = xhr.responseText;
			//console.log(response);
			showMessage(".popup-content", "popup", response);
		}
	};
	xhr.send(formData);
}

function movePrisoner() {
	// Pobierz dane z formularza
	var searchValue = document.querySelector('input[name="search_box1"]').value;
	var selectedDate = document.querySelector('input[name="start_date1"]').value;
	var chooseCell = document.querySelector(".choose_cell"); // Wybierz element <select>
	const selectedCell = chooseCell.value;
	//console.log(selectedCell);

	// Wysyłanie danych na serwer
	var formData = new FormData();
	formData.append("search1", searchValue);
	formData.append("date1", selectedDate);
	formData.append("cell1", selectedCell);

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "move_prisoner.php", true);

	xhr.onload = function () {
		if (xhr.status >= 200 && xhr.status < 300) {
			// Obsługa sukcesu
			var response = xhr.responseText;
			//console.log(response);
			showMessage(".popup-content1", "popup1", response);
		} else {
		}
	};
	xhr.send(formData);
	closeMovePopup();
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

// Inicjowanie zmiennej do przechowywania największej wysokości

function movePopup() {
	const Popup = document.querySelector(".move-popup");
	Popup.style.display = "block";
}

//PRZENOSINY
function load_data2(query) {
	if (query.length > 2) {
		var form_data = new FormData();
		form_data.append("query", query);
		var ajax_request = new XMLHttpRequest();
		ajax_request.open("POST", "process_data2.php");

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
				document.getElementById("search_result1").innerHTML = html;
			}
		};
	} else {
		document.getElementById("search_result1").innerHTML = "";
	}
}

let targetID;
let id;
let data; // Dodaliśmy zmienną do przechowywania danych

function handleSearchResultClick2(event) {
	const target = event.target;

	if (target.name === "prisoner_add") {
		// Pobierz wartość klikniętej sugestii
		const suggestionValue = target.value;

		const targetName = suggestionValue.split(" ")[0];
		const targetSurname = suggestionValue.split(" ")[1];
		targetID = suggestionValue.split(" ")[2];

		// Zaktualizuj pole wprowadzania wybraną sugest
		const searchBox1 = document.querySelector('input[name="search_box1"]');

		searchBox1.value = targetName + " " + targetSurname + ", " + targetID;

		// Wyczyść wyniki wyszukiwania
		document.getElementById("search_result1").innerHTML = "";

		const spanCell = document.querySelector("#currentCell");

		// Ustaw spanCell na wartość cellNumber w przypadku znalezienia pasującego ID
		id = data.find((prisoner) => prisoner.prisoner_id === targetID);
		if (id) {
			spanCell.textContent = `Obecna cela: ${id.cellNumber}`;
		}
		const chooseCell = document.querySelector(".choose_cell");

		chooseCell.querySelectorAll("option").forEach((option) => {
			if (option.value == id.cellNumber) {
				option.disabled = true;
			} else {
				option.disabled = false;
			}
		});
	}
}

const searchBox1 = document.querySelector('input[name="search_box1"]');

searchBox1.addEventListener("keyup", () => {
	const query = searchBox1.value;

	if (query.length >= 3) {
		const formData = new FormData();
		formData.append("query", query);

		fetch("./process_data2.php", {
			method: "POST",
			body: formData,
		})
			.then((response) => response.json())
			.then((fetchedData) => {
				data = fetchedData; // Zapisujemy dane, aby były dostępne globalnie
			})
			.catch((error) => {
				console.error("Błąd pobierania danych:", error);
			});
	}
});

document
	.getElementById("search_result1")
	.addEventListener("click", handleSearchResultClick2);

// Funkcja obsługi zdarzenia zmiany w elemencie select
