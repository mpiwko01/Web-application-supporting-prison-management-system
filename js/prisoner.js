//autosugestia
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
const allId = [...IdPrisoner];

allId.forEach((id) => {
	console.log(id.textContent);
});
dataRows.forEach((row, index) => {
	if (index !== 0) {
		const allNumber = document.createElement("td");
		allNumber.textContent = `${index}.`;
		// Pominięcie pierwszego wiersza (nagłówka)
		const newColumn = document.createElement("td");
		newColumn.innerHTML = '<button class="show_prisoner">Zobacz</button>';
		row.appendChild(newColumn);

		const ShowButtons = newColumn.querySelectorAll(".show_prisoner");
		ShowButtons.forEach((button) => {
			button.addEventListener("click", function () {
				popup.classList.toggle("d-none");
			});
		});

		row.insertBefore(allNumber, row.firstChild);
	}
});

function closePopup() {
	const Popup = document.querySelector(".prisoner-popup");
	popup.classList.add("d-none");
}
