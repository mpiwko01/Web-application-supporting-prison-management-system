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
		editable: true,
		selectable: true,
		unselectAuto: false,
		displayEventTime: false,
		events: myEvents,
		eventRender: function (info) {
			info.el.addEventListener("contextmenu", function (e) {
				e.preventDefault();
				let existingMenu = document.querySelector(".context-menu");
				existingMenu && existingMenu.remove();
				let menu = document.createElement("div");
				menu.className = "context-menu";
				menu.innerHTML = `<ul>
          <li><i class="fas fa-edit"></i>Edytuj</li>
          <li><i class="fas fa-trash-alt"></i>Usuń</li>
          </ul>`;

				const eventIndex = myEvents.findIndex(
					(event) => event.id === info.event.id
				);

				document.body.appendChild(menu);
				menu.style.top = e.pageY + "px";
				menu.style.left = e.pageX + "px";

				// Edit context menu

				menu
					.querySelector("li:first-child")
					.addEventListener("click", function () {
						menu.remove();

						const editModal = new bootstrap.Modal(
							document.getElementById("form")
						);
						const modalTitle = document.getElementById("modal-title");
						const titleInput = document.getElementById("event-title");
						const startDateInput = document.getElementById("start-date");
						const endDateInput = document.getElementById("end-date");
						const colorInput = document.getElementById("event-color");
						const submitButton = document.getElementById("submit-button");
						const cancelButton = document.getElementById("cancel-button");
						modalTitle.innerHTML = "Edit Event";
						titleInput.value = info.event.title;
						startDateInput.value = moment(info.event.start).format(
							"YYYY-MM-DD"
						);
						endDateInput.value = moment(info.event.end, "YYYY-MM-DD")
							.subtract(1, "day")
							.format("YYYY-MM-DD");
						colorInput.value = info.event.backgroundColor;
						submitButton.innerHTML = "Save Changes";

						editModal.show();

						submitButton.classList.remove("btn-success");
						submitButton.classList.add("btn-primary");

						// Edit button

						submitButton.addEventListener("click", function () {
							const updatedEvents = {
								id: info.event.id,
								title: titleInput.value,
								start: startDateInput.value,
								end: moment(endDateInput.value, "YYYY-MM-DD")
									.add(1, "day")
									.format("YYYY-MM-DD"),
								backgroundColor: colorInput.value,
							};

							if (updatedEvents.end <= updatedEvents.start) {
								// add if statement to check end date
								dangerAlert.style.display = "block";
								return;
							}

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
							calendarEvent.setProp(
								"backgroundColor",
								updatedEvents.backgroundColor
							);

							editModal.hide();
						});
					});

				// Delete menu
				menu
					.querySelector("li:last-child")
					.addEventListener("click", function () {
						const deleteModal = new bootstrap.Modal(
							document.getElementById("delete-modal")
						);
						const modalBody = document.getElementById("delete-modal-body");
						const cancelModal = document.getElementById("cancel-button");
						modalBody.innerHTML = `Jesteś pewien, że chcesz usunąć <b>"${info.event.title}"</b>`;
						deleteModal.show();

						const deleteButton = document.getElementById("delete-button");
						deleteButton.addEventListener("click", function () {
							myEvents.splice(eventIndex, 1);
							localStorage.setItem("events", JSON.stringify(myEvents));

							// delete event from data base
							fetch("delete_event.php", {
								method: "POST",
								headers: {
									"Content-Type": "application/json",
								},
								body: JSON.stringify({
									event_id: info.event.id,
								}),
							})
								.then((response) => response.json())
								.then((data) => {
									if (data.status === true) {
										//alert(data.msg);
										//location.reload(); // Odśwież stronę po udanym usunięciu
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
				document.addEventListener("click", function () {
					menu.remove();
				});
			});
		},

		eventDrop: function (info) {
			let myEvents = JSON.parse(localStorage.getItem("events")) || [];
			const eventIndex = myEvents.findIndex(
				(event) => event.id === info.event.id
			);
			const updatedEvent = {
				...myEvents[eventIndex],
				id: info.event.id,
				title: info.event.title,
				start: moment(info.event.start).format("YYYY-MM-DD"),
				end: moment(info.event.end).format("YYYY-MM-DD"),
				backgroundColor: info.event.backgroundColor,
			};
			myEvents.splice(eventIndex, 1, updatedEvent); // Replace old event data with updated event data
			localStorage.setItem("events", JSON.stringify(myEvents));
			console.log(updatedEvent);
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
		const title = document.querySelector("#event-title").value;
		const Date = document.querySelector("#start-date").value;
		const Start = document.querySelector("#start").value;
		const End = document.querySelector("#end").value;
		const color = document.querySelector("#event-color").value;
		//const endDateFormatted = moment(End, "YYYY-MM-DD")
		//.add(1, "day")
		//.format("YYYY-MM-DD");
		let eventId = uuidv4();

		console.log(visitors, prisoner, title, Date, Start, End, color);

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
				start: Start,
				end: End,
				color: color,
				//event_id: eventId,
			}),
		})
			.then((response) => response.json())
			.then((data) => {
				//console.log(data);
				if (data.status === true) {
					alert(data.msg);
					//location.reload();
					eventId = data.event_id;
					//console.log(eventId);
					if (End <= Start) {
						// add if statement to check end date
						dangerAlert.style.display = "block";
						return;
					}

					const newEvent = {
						visitor: visitors,
						prisoner: prisoner,
						id: eventId,
						title: title,
						start: Start,
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
				} else {
					alert(data.msg);
				}
			})
			.catch((error) => {
				console.error("Fetch error:", error);
				alert("An error occurred while processing the request.");
			});

		//console.log(eventId);
	});

	myModal._element.addEventListener("hide.bs.modal", function () {
		dangerAlert.style.display = "none";
		form.reset();
	});
});
