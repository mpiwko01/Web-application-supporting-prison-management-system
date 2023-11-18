document.addEventListener("DOMContentLoaded", function () {
	const calendarEl = document.getElementById("calendar");
	const myModal = new bootstrap.Modal(document.getElementById("form"));
	const dangerAlert = document.getElementById("danger-alert");
	const close = document.querySelector(".btn-close");

	const myEvents = [];

	fetch("get_events.php")
		.then((response) => response.json())
		.then((data) => {
			myEvents.push(...data);
			calendar.addEventSource(myEvents);
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
			today: {
				text: "Dziś",
				click: function () {
					calendar.today();
				},
			},
		},
		header: {
			center: "customButton", // add your custom button here
			right: "today, prev,next ",
		},

		plugins: ["dayGrid", "interaction"],
		allDay: false,
		editable: false,
		selectable: false,
		unselectAuto: false,
		displayEventTime: true,
		displayEventEnd: true,
		events: myEvents,
		eventRender: function (info) {
			//const EventContainer = document.querySelector(".fc-event-container");
			info.el.classList.add("fc-event-pointer");
			info.el.addEventListener("click", function () {
				let foundEvent = myEvents.find((event) => event.id === info.event.id);

				editModal = new bootstrap.Modal(document.getElementById("edit-form"));

				const modalFooter = document.querySelector(".modal-footer");

				const modalTitle = document.getElementById("modal-title");
				document
					.querySelector("#edit-visitor")
					.setAttribute("value", foundEvent.visitors);
				document
					.querySelector("#edit-prisoner")
					.setAttribute("value", foundEvent.prisoner);
				if (foundEvent.title == "Rodzina") {
					document.getElementById("edit-family").checked = true;
				} else if (foundEvent.title == "Znajomy") {
					document.getElementById("edit-friend").checked = true;
				} else if (foundEvent.title == "Prawnik") {
					document.getElementById("edit-attorney").checked = true;
				} else {
					document.getElementById("edit-other").checked = true;
				}
				document
					.querySelector("#edit-start-date")
					.setAttribute("value", foundEvent.start);
				document
					.querySelector("#edit-end")
					.setAttribute("value", foundEvent.end.split(" ")[1]);

				const submitButton = document.getElementById("save-edit-button");
				const cancelButton = document.getElementById("cancel-button");
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
					} else if (document.getElementById("edit-attorney").checked == true) {
						title = document.querySelector("#edit-attorney").value;
						color = "#ff0000";
					} else {
						title = "Inne";
						color = "#F57811";
					}
					const Date = document.querySelector("#edit-start-date").value;
					const End =
						Date.split("T")[0] +
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
							event_name: title,
							date: Date,
							end: End,
							color: color,
							event_id: eventId,
						}),
					})
						.then((response) => response.json())
						.then((data) => {
							if (data.status === true) {
								if (End <= Date) {
									// add if statement to check end date
									dangerAlert.style.display = "block";
									return;
								}
								const updatedEvents = {
									id: info.event.id,
									visitors: visitors,
									prisoner: prisoner,
									title: title,
									start: Date,
									end: End,
									backgroundColor: color,
									color: color,
								};

								const eventIndex = myEvents.findIndex(
									(event) => event.id === updatedEvents.id
								);
								myEvents.splice(eventIndex, 1, updatedEvents);

								localStorage.setItem("events", JSON.stringify(myEvents));

								// Update the event in the calendar
								const calendarEvent = calendar.getEventById(info.event.id);
								calendarEvent.setProp("title", updatedEvents.title);
								calendarEvent.setStart(updatedEvents.start);
								calendarEvent.setEnd(updatedEvents.end);
								calendarEvent.setProp("visitor", updatedEvents.visitor);
								calendarEvent.setProp("prisoner", updatedEvents.prisoner);
								calendarEvent.setProp(
									"backgroundColor",
									updatedEvents.backgroundColor
								);
								calendarEvent.setProp("color", updatedEvents.color);

								//calendar.getEventById(info.event.id).remove();

								myModal.hide();

								form.reset();
								location.reload();
							} else {
								alert(data.msg);
								//myModal.hide()
								//form.reset()
							}
						})
						.catch((error) => {});
					editModal.hide();

					location.reload();
				});

				//podpiecie przyciska usun z EDIT MODAL
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
						// delete event from data base
						fetch("delete_event.php", {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify({
								event_id: deleteID,
							}),
						})
							.then((response) => response.json())
							.then((data) => {
								if (data.status === true) {
									//alert(data.msg);
									location.reload(); // Odśwież stronę po udanym usunięciu
								} else {
									alert(data.msg);
								}
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
		if (startDateInput.value === endDate) {
			endDateInput.value = "";
		}
	});

	calendar.render();

	const form = document.querySelector("form");

	form.addEventListener("submit", function (event) {
		event.preventDefault(); // prevent default form submission

		// retrieve the form input values
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
		const Date = document.querySelector("#start-date").value;
		const onlyDate = Date.split("T")[0];

		const End = document.querySelector("#end").value;
		const FullEnd = onlyDate + "T" + End;

		//const EndFormatted = moment(End).format(`${onlyDate}THH:mm:ss`)
		console.log("NAME: ", prisoner);
		console.log("VISIT: ", visitors);
		// add the new event to data base
		fetch("save_event.php", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				visitor: visitors,
				prisoner: prisoner,
				event_name: title,
				date: Date,
				end: onlyDate + "T" + End,
				color: color,
				//event_id: eventId,
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				if (data.status === true) {
					//alert(data.msg);
					let eventId = data.event_id;
					if (FullEnd <= Date) {
						// add if statement to check end date
						dangerAlert.style.display = "block";
						return;
					}

					const newEvent = {
						visitor: visitors,
						prisoner: prisoner,
						id: eventId,
						title: title,
						end: End,
						date: Date,
						allDay: false,
						backgroundColor: color,
						color: color,
					};

					// add the new event to the myEvents array
					myEvents.push(newEvent);

					// render the new event on the calendar
					calendar.addEvent(newEvent);

					// save events to local storage
					localStorage.setItem("events", JSON.stringify(myEvents));

					myModal.hide();
					form.reset();
					location.reload();
				} else {
					alert(data.msg);
				}
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
});

function handleSearchResultClick(event) {
	const target = event.target;

	if (target.name === "prisoner_add") {
		// Zaktualizuj pole wprowadzania wybraną sugestią
		const searchBox = document.querySelector('input[name="prisoner"]');
		searchBox.value = target.value.split(",")[0];
		// Wyczyść wyniki wyszukiwania
		document.getElementById("search_result").innerHTML = "";
	}
}

document
	.getElementById("search_result")
	.addEventListener("click", handleSearchResultClick);

function load_data(query) {
	if (query.length > 2) {
		var form_data = new FormData();
		form_data.append("query", query);
		var ajax_request = new XMLHttpRequest();
		ajax_request.open("POST", "../process_data.php");
		ajax_request.send(form_data);
		ajax_request.onreadystatechange = function () {
			if (ajax_request.readyState == 4 && ajax_request.status == 200) {
				var response = JSON.parse(ajax_request.responseText);
				var html = '<div class="list-group">';
				if (response.length > 0) {
					for (var count = 0; count < response.length; count++) {
						html +=
							'<input type="submit" class="list-group-item list-group-item-action"  name="prisoner_add" value="' +
							response[count].name +
							" " +
							response[count].surname +
							", " +
							response[count].prisoner_id +
							'">';
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
