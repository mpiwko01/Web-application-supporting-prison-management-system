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
							'<a href="#" class="list-group-item list-group-item-action">' +
							response[count].name +
							" " +
							response[count].surname +
							", " +
							response[count].prisoner_id +
							"</a>";
					}
				} else {
					html +=
						'<a href="#" class="list-group-item list-group-item-action disabled">Brak więźnia w bazie</a>';
				}

				html += "</div>";

				document.getElementById("search_result").innerHTML = html;
			}
		};
	} else {
		document.getElementById("search_result").innerHTML = "";
	}
}

//przycisk show table
const showButton = document.querySelector("#table-btn");

function openTable() {
	showButton.textContent = "Wyświetl wszystko";
	const table = document.querySelector(".table");
	table.classList.toggle("d-none");

	if (!table.classList.contains("d-none")) {
		showButton.textContent = "Schowaj wszystko";
	}
}

showButton.addEventListener("click", openTable);

//profil więźnia
const table = document.querySelector(".my-table");

// Dodaj nowy nagłówek do istniejącej tabeli
const headerRow = table.querySelector("tr");
const newHeaderCell = document.createElement("th");

newHeaderCell.textContent = "Profil więźnia";

headerRow.appendChild(newHeaderCell);

// Dodaj zawartość nowej kolumny do każdego wiersza z danymi
const popup = document.querySelector(".prisoner-popup");
const NrColumn = document.querySelector(".number");
const dataRows = table.querySelectorAll("tr");
const IdPrisoner = document.querySelectorAll(".id_data");

let allId = [];
IdPrisoner.forEach((id) => {
	const valueID = id.innerHTML;
	allId.push(valueID);
	console.log(valueID);
});
console.log(allId);

// Funkcja do pobierania danych więźnia z serwera
function fetchPrisonerData(prisonerId) {
	return fetch("./show_prisoner.php", {
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

// Obiekt do przechowywania przygotowanych danych więźniów
const prisonerData = {};

// Przygotuj dane więźniów wcześniej
allId.forEach((prisonerId) => {
	fetchPrisonerData(prisonerId).then((data) => {
		prisonerData[prisonerId] = data[0];
	});
});

// Nasłuchiwanie przycisków "Zobacz" wierszy tabeli
dataRows.forEach((row, index) => {
	if (index !== 0) {
		const allNumber = document.createElement("td");
		allNumber.textContent = `${index}.`;
		// Pominięcie pierwszego wiersza (nagłówka)
		const newColumn = document.createElement("td");
		newColumn.innerHTML = '<button class="show_prisoner">Zobacz</button>';
		row.appendChild(newColumn);
		row.insertBefore(allNumber, row.firstChild);

		const ShowButtons = newColumn.querySelectorAll(".show_prisoner");
		ShowButtons.forEach((button) => {
			const row = button.closest("tr");
			const prisonerId = row.querySelector(".id_data").textContent;

			button.addEventListener("click", function () {
				// Pobierz przygotowane dane więźnia i wyświetl je
				const prisoner = prisonerData[prisonerId];

				const prisonerName = document.querySelector(".space_name");
				const prisonerSurname = document.querySelector(".space_surname");
				const prisonerSex = document.querySelector(".space_sex");
				const prisonerBirthDate = document.querySelector(".space_birth_date");
				const prisonerAge = document.querySelector(".space_age");
				const prisonerCell = document.querySelector(".space_cell");

				prisonerName.textContent = prisoner.name;
				prisonerSurname.textContent = prisoner.surname;
				prisonerSurname.textContent = prisoner.surname;
				prisonerSex.textContent =
					prisoner.sex === "F" ? "kobieta" : "mężczyzna";
				prisonerBirthDate.textContent = prisoner.birthDate;

				const birthDateConverted = new Date(prisoner.birthDate);
				const currentDate = new Date();
				const age =
					currentDate.getFullYear() - birthDateConverted.getFullYear();
				prisonerAge.textContent = age;

				prisonerCell.textContent = prisoner.cellNumber;

				// Wyświetlenie popupu
				popup.classList.remove("d-none");
			});
		});
	}
});
function closePopup() {
	popup.classList.add("d-none");
	const data = document.querySelector(".data");
}

function openPopup() {
	document.getElementById("popup").style.display = "block";
}

function closePopupAdd() {
	document.getElementById("popup").style.display = "none";
}

