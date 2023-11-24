document.addEventListener("DOMContentLoaded", function () {
	const calendarEl = document.getElementById("calendar");
	const myModal = new bootstrap.Modal(document.getElementById("form"));
	const passesModal = new bootstrap.Modal(
		document.getElementById("passes_modal")
	);
	const deletePass = new bootstrap.Modal(
		document.getElementById("delete_pass")
	);
	const dangerAlert = document.getElementById("danger-alert");
	const close = document.querySelector(".btn-close");

	const myEvents = [];
	const myPasses = [];

	//Pobieranie wszystkich wydarzeń z bazy
	fetch("get_events.php")
		.then((response) => response.json())
		.then((data) => {
			const events = data.map((event) => ({ ...event, eventType: "event" }));
			myEvents.push(...events);
			calendar.addEventSource(events);
		})
		.catch((error) => {
			console.error("Błąd pobierania danych z serwera:", error);
		});

	fetch("get_passes.php")
		.then((response) => response.json())
		.then((data) => {
			const passes = data.map((pass) => ({ ...pass, eventType: "pass" }));
			myPasses.push(...passes);
			calendar.addEventSource(passes);
		})
		.catch((error) => {
			console.error("Błąd pobierania danych z serwera:", error);
		});

	const calendar = new FullCalendar.Calendar(calendarEl, {
		locale: "pl",
		customButtons: {
			customButton: {
				text: "Dodaj odwiedziny",
				click: function () {
					myModal.show();
					const modalTitle = document.getElementById("modal-title");
					const submitButton = document.getElementById("submit-button");
					modalTitle.innerHTML = "Dodaj odwiedziny";
					submitButton.innerHTML = "Dodaj";
					submitButton.classList.remove("btn-primary");
					submitButton.classList.add("btn-success");

					// Nasłuchuj zdarzenia "click" na przycisku "Dodaj"
					close.addEventListener("click", () => {
						myModal.hide();
					});
				},
			},
			customButton2: {
				text: "Wprowadź przepustkę",
				click: function () {
					passesModal.show();
				},
			},
			today: {
				text: "Dziś",
				click: function () {
					calendar.today();
				},
			},
		},
		header: {
			center: "customButton customButton2", // add your custom button here
			right: "today, prev,next ",
		},

		plugins: ["dayGrid", "interaction"],
		allDay: true, // eventy defaultowo trwają cały dzień
		editable: false, // eventów nie można przesuwać za pomocą myszki
		selectable: false, // użytkownik nie może wybrać kilku dni za pomocą myszki
		displayEventTime: true, // wyświetlanie początkowej godziny eventu
		displayEventEnd: true, // wyświetlanie końcowej godziny eventu
		events: myEvents,
		eventRender: function (info) {
			info.el.classList.add("fc-event-pointer");
			info.el.addEventListener("click", function () {
				const eventType = info.el.querySelectorAll("span").length; //zliczam spany, aby na podstawie ich liczby określić później czy to spotkanie, czy przepustka
				let foundEvent = myEvents.find((event) => event.id == info.event.id);
				if (foundEvent && eventType == 2) {
					editModal = new bootstrap.Modal(document.getElementById("edit-form"));

					document
						.querySelector("#edit-visitor")
						.setAttribute("value", foundEvent.visitors);
					document
						.querySelector("#edit-prisoner")
						.setAttribute("value", foundEvent.title);
					if (foundEvent.type == "Rodzina")
						document.getElementById("edit-family").checked = true;
					else if (foundEvent.type == "Znajomy")
						document.getElementById("edit-friend").checked = true;
					else if (foundEvent.type == "Prawnik")
						document.getElementById("edit-attorney").checked = true;
					else document.getElementById("edit-other").checked = true;
					document
						.querySelector("#edit-start-date")
						.setAttribute("value", foundEvent.start);
					document
						.querySelector("#edit-end")
						.setAttribute("value", foundEvent.end.split(" ")[1]);

					const submitButton = document.getElementById("save-edit-button");
					const deleteButton = document.querySelector("#delete-event-button");

					submitButton.innerHTML = "Zapisz zmiany";

					submitButton.classList.remove("btn-success");
					submitButton.classList.add("btn-primary");

					// Edit button
					submitButton.addEventListener("click", function () {
						const visitors = document.querySelector("#edit-visitor").value;
						document.querySelector("#edit-visitor").setAttribute(
							"value",
							(function () {
								return;
							})()
						);
						const prisoner = document.querySelector("#edit-prisoner").value;
						let title = "Inne";
						let color = "#3788d8";
						if (document.getElementById("edit-family").checked == true) {
							title = document.querySelector("#edit-family").value;
							color = "#008000";
						} else if (document.getElementById("edit-friend").checked == true) {
							title = document.querySelector("#edit-friend").value;
							color = "#3788d8";
						} else if (
							document.getElementById("edit-attorney").checked == true
						) {
							title = document.querySelector("#edit-attorney").value;
							color = "#ff0000";
						} else {
							title = "Inne";
							color = "#F57811";
						}
						const date = document.querySelector("#edit-start-date").value;
						const end =
							date.split("T")[0] +
							"T" +
							document.querySelector("#edit-end").value;
						const eventId = foundEvent.id;
						fetch("edit_event.php", {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify({
								visitor: visitors,
								prisoner: prisoner,
								eventName: title,
								date: date,
								end: end,
								color: color,
								eventId: eventId,
							}),
						})
							.then((response) => response.json())
							.then((data) => {
								if (data.status === true) {
									if (end <= date) {
										// add if statement to check end date
										dangerAlert.style.display = "block";
										return;
									}
									myModal.hide();
									form.reset();
									location.reload();
								} else alert(data.msg);
							})
							.catch(() => {});
						editModal.hide();
						location.reload();
					});

					//Podpięcie przycisku usuń z EDIT MODAL
					deleteButton.addEventListener("click", function () {
						editModal.hide();
						const deleteModal = new bootstrap.Modal(
							document.getElementById("delete-modal")
						);
						const modalBody = document.getElementById("delete-modal-body");
						const cancelModal = document.getElementById("cancel-button");
						modalBody.innerHTML = `Jesteś pewien, że chcesz usunąć <b>"${foundEvent.title}"</b>`;
						const deleteID = foundEvent.id;
						deleteModal.show();
						const deleteButton2 = document.getElementById("delete-button");
						deleteButton2.addEventListener("click", function () {
							myEvents.splice(deleteID, 1);
							localStorage.setItem("events", JSON.stringify(myEvents));

							// Usuwanie wydarzenia z bazy
							fetch("delete_event.php", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
								},
								body: JSON.stringify({
									eventId: deleteID,
								}),
							})
								.then((response) => response.json())
								.then((data) => {
									if (data.status === true)
										location.reload(); // Odśwież stronę po udanym usunięciu
									else alert(data.msg);
								})
								.catch((error) => {
									console.error(
										"Wystąpił błąd podczas usuwania wydarzenia:",
										error
									);
									alert("Wystąpił błąd podczas przetwarzania żądania.");
								});
							calendar.getEventById(info.event.id).remove();
							deleteModal.hide();
							menu.remove();
						});
						cancelModal.addEventListener("click", function () {
							deleteModal.hide();
						});
					});
					editModal.show();
				} else if (foundEvent && eventType == 1) {
					deletePass.show();
					const cancelButton = document.querySelector("#cancel-button_pass");
					const deleteButton = document.querySelector("#delete-button_pass");
					const deleteID = foundEvent.id;
					cancelButton.addEventListener("click", () => {
						deletePass.hide();
					});
					deleteButton.addEventListener("click", () => {
						myEvents.splice(deleteID, 1);
						localStorage.setItem("events", JSON.stringify(myEvents));

						// Usuwanie przepustki z bazy
						fetch("delete_pass.php", {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify({
								eventId: deleteID,
							}),
						})
							.then((response) => response.json())
							.then((data) => {
								if (data.status === true)
									location.reload(); // Odśwież stronę po udanym usunięciu
								else alert(data.msg);
							})
							.catch((error) => {
								console.error(
									"Wystąpił błąd podczas usuwania wydarzenia:",
									error
								);
								alert("Wystąpił błąd podczas przetwarzania żądania.");
							});
						calendar.getEventById(info.event.id).remove();
						deletePass.hide();
					});
				}
			});
		},
	});

	calendar.on("select", function (info) {
		const startDateInput = document.getElementById("start-date");
		const endDateInput = document.getElementById("end-date");
		startDateInput.value = info.startStr;
		const endDate = moment(info.endStr, "YYYY-MM-DD")
			.subtract(1, "day")
			.format("YYYY-MM-DD");
		endDateInput.value = endDate;
		if (startDateInput.value === endDate) endDateInput.value = "";
	});

	calendar.render();

	const form = document.querySelector("#myForm");

	form.addEventListener("submit", function (event) {
		event.preventDefault(); // prevent default form submission

		// Pozyskiwanie wartości z pól formularza
		const visitors = document.querySelector("#visitor").value;
		const prisoner = document.querySelector("#prisoner").value;
		let title = "Inne";
		let color = "#3788d8";

		if (document.getElementById("family").checked == true) {
			title = document.querySelector("#family").value;
			color = "#008000";
		} else if (document.getElementById("friend").checked == true) {
			title = document.querySelector("#friend").value;
			color = "#3788d8";
		} else if (document.getElementById("attorney").checked == true) {
			title = document.querySelector("#attorney").value;
			color = "#ff0000";
		} else {
			title = "Inne";
			color = "#F57811";
		}
		const date = document.querySelector("#start-date").value;
		const onlyDate = date.split("T")[0];

		const end = document.querySelector("#end").value;
		const fullEnd = onlyDate + "T" + end;

		// Dodawanie nowego eventu do bazy danych
		fetch("save_event.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				visitor: visitors,
				prisoner: prisoner,
				eventName: title,
				date: date,
				end: fullEnd,
				color: color,
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.status === true) {
					let eventId = data.eventId;
					if (fullEnd <= date) {
						// add if statement to check end date
						dangerAlert.style.display = "block";
						return;
					}
					const newEvent = {
						visitor: visitors,
						prisoner: prisoner,
						id: eventId,
						title: title,
						end: end,
						date: date,
						allDay: false,
						backgroundColor: color,
						color: color,
					};

					myEvents.push(newEvent); // Dodaj nowy event do tablicy myEvents
					calendar.addEvent(newEvent); // Pokaż nowy event w kalnedarzu
					localStorage.setItem("events", JSON.stringify(myEvents)); // Zapisz eventy do local storage

					myModal.hide();
					form.reset();
					location.reload();
				} else alert(data.msg);
			})
			.catch((error) => {
				console.error("Fetch error:", error);
				alert("An error occurred while processing the request.");
			});
	});

	myModal._element.addEventListener("hide.bs.modal", function () {
		dangerAlert.style.display = "none";
		form.reset();
	});

	const form2 = document.querySelector(".form_passes");

	form2.addEventListener("submit", function (event) {
		event.preventDefault();

		passesModal.hide();

		const who = document.querySelector("#prisoner1").value;
		const startPass = document.querySelector(".start_pass").value;
		const endPass = document.querySelector(".end_pass").value;

		fetch("passes.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				prisoner1: who,
				startPass: startPass,
				endPass: endPass,
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.status === true)
					location.reload(); // Odśwież stronę po udanym usunięciu
				else alert(data.msg);
			})
			.catch((error) => {
				console.error("Wystąpił błąd podczas usuwania wydarzenia:", error);
				alert("Wystąpił błąd podczas przetwarzania żądania.");
			});
	});
});

function handleSearchResultClick(event) {
	const target = event.target;

	if (target.name === "prisoner_add") {
		// Zaktualizuj pole wprowadzania wybraną sugestią
		const searchBox = document.querySelector('input[name="prisoner"]');
		searchBox.value = target.value.split(",")[0];
		// Wyczyść wyniki wyszukiwania
		document.getElementById("search_result").innerHTML = "";
	} else if (target.name === "prisoner_pass") {
		// Zaktualizuj pole wprowadzania wybraną sugestią
		const searchBox = document.querySelector('input[name="prisoner1"]');
		searchBox.value = target.value.split(",")[0];
		// Wyczyść wyniki wyszukiwania
		document.getElementById("search_result1").innerHTML = "";
	}
}

document
	.getElementById("search_result")
	.addEventListener("click", handleSearchResultClick);

document
	.getElementById("search_result1")
	.addEventListener("click", handleSearchResultClick);

// funkcja ładująca dane (więźniów) do autosugestii
function loadData(query, id, type) {
	if (query.length > 2) {
		let formData = new FormData();
		formData.append("query", query);
		let ajaxRequest = new XMLHttpRequest();
		ajaxRequest.open("POST", "../process_data.php");
		ajaxRequest.send(formData);
		ajaxRequest.onreadystatechange = function () {
			if (ajaxRequest.readyState == 4 && ajaxRequest.status == 200) {
				const response = JSON.parse(ajaxRequest.responseText);
				let html = '<div class="list-group">';
				if (response.length > 0) {
					for (var count = 0; count < response.length; count++) {
						html +=
							'<input type="submit" class="list-group-item list-group-item-action"  name=' +
							type +
							' value="' +
							response[count].name +
							" " +
							response[count].surname +
							", " +
							response[count].prisoner_id +
							'">';
					}
				} else
					html +=
						'<a href="#" class="list-group-item list-group-item-action disabled">Brak więźnia</a>';
				html += "</div>";
				document.getElementById(id).innerHTML = html;
			}
		};
	} else document.getElementById(id).innerHTML = "";
}
