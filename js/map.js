document.addEventListener("DOMContentLoaded", () => {

	const popupAdd = new bootstrap.Modal(document.querySelector(".popup-add"));

	const popupMove = new bootstrap.Modal(document.querySelector(".popup-move"));

	const relationsPopup = new bootstrap.Modal(document.querySelector(".popup_relations"));

	const messagePopup = new bootstrap.Modal(document.querySelector(".message-popup"));

	const Cells = document.querySelectorAll(".prison_cell");

	var button1 = document.getElementById("btn-1");
	var button2 = document.getElementById("btn-2");
	var button3 = document.getElementById("btn-3");
	var button4 = document.getElementById("btn-4");
	var button5 = document.getElementById("btn-5");
	var button6 = document.getElementById("btn-6");
	var button7 = document.getElementById("btn-7");
	var button8 = document.getElementById("btn-8");
	var button9 = document.getElementById("btn-9");
	var button10 = document.getElementById("btn-10");
	var button11 = document.getElementById("btn-11");
	var button12 = document.getElementById("btn-11");

	var buttonsArray = [
		0,
		button1,
		button2,
		button3,
		button4,
		button5,
		button6,
		button7,
		button8,
		button9,
		button10,
		button11,
		button12,
	];

	const cellElements = document.querySelectorAll(".nr_celi");
	const cellNumbers = [];

	const cellButtons = {};

	cellElements.forEach((element) => {
		const cellText = element.textContent.trim();
		const lastChar = cellText.split(" ")[2];

		cellNumbers.push(lastChar);
		const buttonSelector = `#btn-${lastChar}`;
		cellButtons[lastChar] = buttonSelector;
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
					const age = prisoner.age;
					const isReoffender = prisoner.isReoffender;
					const severity = prisoner.severity;
					const fromDate = prisoner.fromDate;

					const prisonerElement = document.createElement("span");
					prisonerElement.classList.add("prisoner");
					prisonerElement.textContent = `${name} ${surname}\n` +
						`Age: ${age}\n` +
						`Reoffender: ${isReoffender ? 'No' : 'Yes'}\n` +
						`Severity: ${severity}\n` +
						`From Date: ${fromDate}\n`;
					prisonerElement.style.whiteSpace = "pre";

					const ThisCell = document.querySelectorAll(
						`.prison_cell[id="${cellNumber}"]`
					);

					ThisCell.forEach((cell) => {
						let CellElement = cell.querySelector(".space_for_prisoners");
						CellElement.appendChild(prisonerElement);
					});
				});
				IsCellTaken();

				setHeight();
			})
			.catch((error) => {
				console.error("Błąd pobierania danych:", error);
			});
	}

	function setHeight() {
		// Funkcja ustawiająca tę samą wysokość dla wszystkich elementów
		let maxHeight = 0;
		const elements = document.querySelectorAll(".prison_cell");
		// Znalezienie największej wysokości
		elements.forEach((element) => {
			const elementHeight = element.offsetHeight;
			if (elementHeight > maxHeight) {
				maxHeight = elementHeight;
			}
		});
		elements.forEach((element) => {
			element.style.height = `${maxHeight}px`;
		});
	}

	loadPrisoners();

	function IsCellTaken() {
		const moveButton = document.querySelector(".move");
		
		Cells.forEach((item) => {
			let currentCellPrisoners = 0; //zmienna zbierająca liczbę więźniów w obecnej celi
			console.log("item: ", item);
			const List = item.querySelector(".list_of");
			const prisonerDiv = item.querySelector(".space_for_prisoners");
			const spanPrisoner = prisonerDiv.querySelectorAll(".prisoner");
			spanPrisoner.forEach((prisoner) => {
				currentCellPrisoners += 1; // Dodaje do zmiennej w pętli 1 za każdego więźnia
			});

			if (currentCellPrisoners == 0) {
				// Sprawdzam liczbę więźniów w aktualnej celi
				item.style.backgroundColor = "#a3d7a3"; // Brak więźniów w celi to kolor zielony
				List.innerHTML = "PUSTA CELA"; //Zmieniam wyświetlany tekst na "PUSTA CELA"
			} else if (currentCellPrisoners > 0 && currentCellPrisoners < 4) {
				item.style.backgroundColor = "#ffbd23"; // Jeśli są więźniowie, ale jest jeszcze miejsce to kolor celi jest pomarańczowy
				List.innerHTML = "Osadzeni:";
				moveButton.classList.remove("d-none");
				
			} else {
				item.style.backgroundColor = "#fb8b8b"; //Jeśli jest osiągnięty limit miejsc to kolor celi jest czerwony
				List.innerHTML = "Osadzeni:";
				moveButton.classList.remove("d-none");
			}
		});
		setHeight();
	}


	function handleClick(event) {
		//document.getElementById("popup").style.display = "block";
		popupAdd.show();
		var clickedButton = event.target;
		var cell_number = buttonsArray.indexOf(clickedButton);
		localStorage.setItem("cell", cell_number);
		var cell = localStorage.getItem("cell");
		console.log(cell);
	}

	const move = document.querySelector('.move');

	move.addEventListener("click", () => {
		popupMove.show();
	})

	var buttons = document.querySelectorAll(".btn-add");
	buttons.forEach(function (button) {
		button.addEventListener("click", handleClick);
	});

	function closePopup(popupId) {
		//var popup = document.getElementById(popupId);
		if (popupId === "popup") {
			var popup = document.getElementById("popup");
			var popupContent = document.querySelector(".popup-content");
			popupContent.innerHTML = originalPopupContent;
			popupContent.style.display = "flex";
			popupContent.style.flexDirection = "column";
			popup.style.display = "none";
			var searchResult = document.getElementById("search_result");
			sessionStorage.removeItem("prisonerAddedDisplayed");
			document
				.getElementById("search_result")
				.addEventListener("click", handleSearchResultClick);
			loadPrisoners();
			loadPrisonersWithoutCellHistory();
			searchResult.innerHTML = "";
		} else if (popupId === "popup1") {
			var popup = document.getElementById("popup1");
			var popupContent1 = document.querySelector(".popup-content1");
			popupContent1.innerHTML = originalPopupContent1;
			popupContent1.style.display = "flex";
			popupContent1.style.flexDirection = "column";
			popup.style.display = "none";
			var searchResult = document.getElementById("search_result1");
			sessionStorage.removeItem("prisonerAddedDisplayed");
			document
				.getElementById("search_result1")
				.addEventListener("click", handleSearchResultClick2);
			loadPrisoners();
			loadPrisonersWithoutCellHistory();
			searchResult.innerHTML = "";
		} else if (popupId === "relations_popup") {
			const popup = document.querySelector("#relations_popup");
			const resultsDiv = document.querySelector(".results");
			popup.classList.add("d-none");
			resultsDiv.textContent = "";
		}
	}

	var originalPopupContent = document.querySelector(".popup-content").innerHTML;
	var originalPopupContent1 = document.querySelector(".popup-content1").innerHTML;

	const buttonAdd = document.querySelector('.btn-prisoner-add');

	buttonAdd.addEventListener("click", addPrisoner);

	const buttonMove = document.querySelector('.btn-prisoner-move');

	buttonMove.addEventListener("click", movePrisoner);

	

	function showMessage(place, message) {
		const success = document.querySelector(place);
		success.innerHTML = message;	
	}

	function addPrisoner() {
		// Pobierz dane z formularza
		var searchValue = document.querySelector('input[name="search_box"]').value;
		var selectedDate = document.querySelector('input[name="start_date"]').value;

		//var searchValueParts = searchValue.split(', ');
		//var name = searchValueParts[0];
		//var prisoner_id = searchValueParts[1];
		//console.log(name); //dziala
		//console.log(prisoner_id); //dziala

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
				console.log(response);
				popupAdd.hide();
				//if (response == "Dodano więźnia do celi.") showMessage(".message", response);
				//else showMessage(".message-long", response);
				showMessage(".message", response);
				messagePopup.show();
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

		console.log(formData);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "move_prisoner.php", true);

		xhr.onload = function () {
			if (xhr.status >= 200 && xhr.status < 300) {
				// Obsługa sukcesu
				var response = xhr.responseText;
				console.log(response);
				popupMove.hide();
				//if (response == "Dodano więźnia do celi.") showMessage(".message", response);
				showMessage(".message", response);
				//else showMessage(".message-long", response);
				messagePopup.show();
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
			searchBox.value = targetName + " " + targetSurname + ", " + targetID;
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

			fetch("./cell_history.php")
				.then((response) => response.json())
				.then((data) => {
					console.log(data);
					data.forEach((prisoner) => {
						const id = prisoner.id;
						const cell = prisoner.cellNumber;
						console.log(id, cell);
						if (targetID === id) {
							spanCell.textContent = `Obecna cela: ${cell}`;

							const chooseCell = document.querySelector(".choose_cell");

							chooseCell.querySelectorAll("option").forEach((option) => {
								if (option.value == cell) {
									option.disabled = true;
								} else {
									option.disabled = false;
								}
							});
						}
					});
				});
		}
	}

	document
		.getElementById("search_result1")
		.addEventListener("click", handleSearchResultClick2);

	function loadPrisonersWithoutCellHistory() {
		const prisonerList = document.querySelector(".prisoner-list");

		fetch("./show_unassigned.php")
			.then((response) => response.json())
			.then((data) => {
				if (data.length > 0) {
					const prisonersNames = data.map(
						(prisoner) => `${prisoner.name} ${prisoner.surname}`
					);

					prisonerList.innerHTML = prisonersNames.join(`,<br>`);
				} else {
					prisonerList.textContent = "Brak więźniów bez historii celi.";
				}
			})
			.catch((error) => {
				console.error("Błąd pobierania danych:", error);
			});
	}

	//Obsługa pięter
	let floor = 1;
	const FloorButton = document.querySelector(".floor");
	const FloorNumber = document.querySelector(".floor_number");

	FloorNumber.textContent = "PIĘTRO 1 - przeznaczone dla kobiet.";

	function toggleFloor() {
		const cellsFloor1 = document.querySelectorAll(".prison_cell:nth-child(-n+6)");
		const cellsFloor2 = document.querySelectorAll(".prison_cell:nth-child(n+7)");

		cellsFloor1.forEach((cell) => {
			cell.classList.toggle("d-none");
		});

		cellsFloor2.forEach((cell) => {
			cell.classList.toggle("d-none");
		});

		if (floor === 1) {
			FloorButton.innerHTML = `<i class="fas fa-chevron-left"></i> Piętro 2`;
			FloorNumber.textContent = "PIĘTRO 2 - przeznaczone dla mężczyzn.";
			floor = 2;
		} else {
			FloorButton.innerHTML = `Piętro 1 <i class="fas fa-chevron-right"></i>`;
			FloorNumber.textContent = "PIĘTRO 1 - przeznaczone dla kobiet.";
			floor = 1;
		}
	}

	FloorButton.addEventListener("click", toggleFloor);

	// Wywołaj funkcję, aby załadować i wyświetlić więźniów
	loadPrisonersWithoutCellHistory();

	//relacje więźniów
	const showButton = document.querySelector(".relations");

	function openTable() {
		showButton.textContent = "Zobacz powiązania więźniów";
		const table = document.querySelector(".table");
		table.classList.toggle("d-none");

		if (!table.classList.contains("d-none")) {
			showButton.textContent = "Schowaj";
		}
	}

	showButton.addEventListener("click", openTable);

	const table = document.querySelector(".table");
	const dataRows = table.querySelectorAll("tr");

	const headerRow = table.querySelector("tr");
	const newHeaderCell = document.createElement("th");

	newHeaderCell.textContent = "Powiązania";

	headerRow.appendChild(newHeaderCell);

	dataRows.forEach((row, index) => {
		if (index !== 0) {
			const allNumber = document.createElement("td");
			allNumber.textContent = `${index}.`;
			// Pominięcie pierwszego wiersza (nagłówka)
			const newColumn = document.createElement("td");
			newColumn.innerHTML = '<button class="show_relations">Zobacz</button>';
			row.appendChild(newColumn);
			row.insertBefore(allNumber, row.firstChild);

			const ShowButtons = newColumn.querySelectorAll(".show_relations");
			//FUNKCJA POBIERANIA DANYCH POWIĄZAŃ WIĘŹNIA

			ShowButtons.forEach((button) => {
				const popup = document.querySelector(".popup_relations");
				const row = button.closest("tr");
				const prisonerId = row.querySelector(".id_data").textContent;
				fetchPrisonerData(prisonerId);
				button.addEventListener("click", () => {
					displayPrisonerInfo(prisonerId);
					relationsPopup.show();
					console.log(prisonerId);
				});
			});
		}
	});

	const IdPrisoner = document.querySelectorAll(".id_data");

	let allId = [];
	IdPrisoner.forEach((id) => {
		const valueID = id.innerHTML;
		allId.push(valueID);
		console.log(valueID);
	});
	console.log(allId);

	const prisonerData = {};

	// Przygotuj dane więźniów wcześniej
	allId.forEach((prisonerId) => {
		fetchPrisonerData(prisonerId).then((data) => {
			prisonerData[prisonerId] = data;
		});
	});

	function fetchPrisonerData(prisonerId) {
		return fetch("relations.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({ allId: [prisonerId] }),
		})
			.then((response) => response.json())
			.catch((error) => {
				console.error("Błąd pobierania danych więźnia:", error);
			});
	}

	let fetchedData = {};

	function GetNames() {
		fetch("select_all_json.php")
			.then((response) => response.json())
			.then((data) => {
				fetchedData = data;
				console.log(fetchedData);
			});
	}
	GetNames();

	function displayPrisonerInfo(ID) {
		const prisoners = prisonerData[ID];
		const resultsDiv = document.querySelector(".results");

		if (!prisoners || prisoners.message === "Brak powiązań") {
			resultsDiv.textContent = "Brak powiązań";
		} else {
			resultsDiv.innerHTML = "";

			let wspolwiezniCounter = 0;

			prisoners.forEach((prisoner) => {
				if (prisoner.id === ID) {
					const who = document.createElement("span");
					const where = document.createElement("span");
					const when = document.createElement("span");
					const matchingId = Object.values(fetchedData).find(
						(item) => item.id === prisoner.id2
					);
					if (matchingId) {
						wspolwiezniCounter++;
						who.innerHTML = `${wspolwiezniCounter}. <strong>Współwięzień:</strong> ${matchingId.name} ${matchingId.surname}</br>`;
					}
					//who.textContent = fetchedData[prisoner.id2].name

					where.innerHTML = `<strong>W której celi?</strong> ${prisoner.cellNumber}</br>`;

					if (prisoner.to === null) {
						prisoner.to = "obecnie";
					}
					console.log(typeof prisoner.to);
					when.innerHTML = `<strong>Kiedy?</strong> od ${prisoner.from} do ${prisoner.to}</br></br>`;

					resultsDiv.appendChild(who);
					resultsDiv.appendChild(where);
					resultsDiv.appendChild(when);
				}
			});
		}
	}

	// function FetchRelations() {
	// 	fetch("relations.php")
	// 		.then((response) => response.json())
	// 		.then((data) => {
	// 			data.forEach((prisoner) => {
	// 				const id1 = prisoner.id;
	// 				const id2 = prisoner.id2;
	// 				const cell = prisoner.cellNumber;
	// 				const from = prisoner.from;
	// 				const to = prisoner.to;
	// 			});
	// 		});
	// }
});

function load_data(query) {
	if (query.length > 0) {
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
							'<input type="button" class="list-group-item list-group-item-action"  name="prisoner_add" value="' +
							response[count].name +
							" " +
							response[count].surname +
							", " +
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

//PRZENOSINY
function load_data2(query) {
	if (query.length > 0) {
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
							'<input type="submit" class="list-group-item list-group-item-action" name="prisoner_add" value="' +
							response[count].name +
							" " +
							response[count].surname +
							", " +
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
						'<a href="#" class="list-group-item list-group-item-action disabled">Taki więzień nie przebywa w żadnej celi</a>';
				}
				html += "</div>";
				document.getElementById("search_result1").innerHTML = html;
			}
		};
	} else {
		document.getElementById("search_result1").innerHTML = "";
	}
}

