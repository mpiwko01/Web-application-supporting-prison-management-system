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

function addPrisonerToDatabase() {
	// Pobierz dane z formularza
	var name = document.querySelector('input[name="name_input"]').value;
	console.log(name);
	var surname = document.querySelector('input[name="surname_input"]').value;
	console.log(surname);
	var sex = document.querySelector(".sex_input").value;
	console.log(sex);
	var birthDate = document.querySelector(
		'input[name="birth_date_input"]'
	).value;
	console.log(birthDate);
	var street = document.querySelector('input[name="street_input"]').value;
	console.log(street);
	var houseNumber = document.querySelector(
		'input[name="house_number_input"]'
	).value;
	console.log(houseNumber);
	var city = document.querySelector('input[name="city_input"]').value;
	console.log(city);
	var zipCode = document.querySelector('input[name="zip_code_input"]').value;
	console.log(zipCode);
	var startDate = document.querySelector(
		'input[name="start_date_input"]'
	).value;
	console.log(startDate);
	var endDate = document.querySelector('input[name="end_date_input"]').value;
	console.log(endDate);
	var crime = document.querySelector(".crime_input").value;
	console.log(crime);

	// Wysyłanie danych na serwer
	var formData = new FormData();
	formData.append("name", name);
	formData.append("surname", surname);
	formData.append("sex", sex);
	formData.append("birthDate", birthDate);
	formData.append("street", street);
	formData.append("houseNumber", houseNumber);
	formData.append("city", city);
	formData.append("zipCode", zipCode);
	formData.append("startDate", startDate);
	formData.append("endDate", endDate);
	formData.append("crime", crime);

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "add_prisoner_to_database.php", true);

	xhr.onload = function () {
		//console.log(xhr.status);
		if (xhr.status >= 200 && xhr.status < 300) {
			var response = xhr.responseText;
			//console.log(response);
			showMessage(".popup-content", "popup", response);
		} else {
			//console.error("Błąd podczas wysyłania żądania.");
		}
	};

	xhr.send(formData);
}

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

function togglePopup(popupClassName) {
	const popup = document.querySelector(`.${popupClassName}`);
	if (popup.classList.contains("d-none")) {
		popup.classList.remove("d-none");
	} else {
		popup.classList.add("d-none");
	}
}

function openPopup() {
	document.querySelector(".pop").classList.remove("d-none");
}
