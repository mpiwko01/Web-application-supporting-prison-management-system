const PasswordModal = new bootstrap.Modal(
	document.getElementById("password_modal")
);

const PasswordModalCom = new bootstrap.Modal(
	document.getElementById("password_modal_com")
);

const EmployeeModalCom = new bootstrap.Modal(document.querySelector(".employee_modal_com"));

const employeeModal = new bootstrap.Modal(
	document.querySelector(".employee-popup")
);

const employeeListModal = new bootstrap.Modal(
	document.querySelector(".employee-list-popup")
);

const archiveListModal = new bootstrap.Modal(
	document.querySelector(".archive-list-popup")
);

const deleteModal = new bootstrap.Modal(
	document.querySelector(".delete-popup")
);

const employeeListButton = document.querySelector(".employee-list-button");

employeeListButton.addEventListener("click", function() {
	employeeListModal.show();
});

const archiveListButton = document.querySelector(".archive-list-button");

archiveListButton.addEventListener("click", function() {
	archiveListModal.show();
});

const passwordButton = document.querySelector('.password-button');

passwordButton.addEventListener("click", function() {
	PasswordModal.show();
});



const cancelButton = document.querySelector('.cancel-button');

cancelButton.addEventListener("click", function() {
	deleteModal.hide();
});

const closeButton = document.querySelector('.btn-close');

closeButton.addEventListener("click", () => {
	PasswordModal.hide();
	PasswordModalCom.hide();
	employeeModal.hide();
});

const employeeButton = document.querySelector(".employee-button");

employeeButton.addEventListener("click", () => {
	clearErrors('.employee-popup');
	clearInputs('.employee-popup');
	employeeModal.show();
});

function changePassword() {

	var oldPassword = document.querySelector('input[name="old_password"]').value;
	var password1 = document.querySelector('input[name="password1"]').value;
	var password2 = document.querySelector('input[name="password2"]').value;

	var formData = new FormData();
	formData.append("oldPassword", oldPassword);
	formData.append("password1", password1);
	formData.append("password2", password2);

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "password_change.php", true);

	clearError();

	xhr.onload = function () {
		if (xhr.status >= 200 && xhr.status < 300) {
			var response = xhr.responseText;
			console.log(response);
			if (response == "Zmieniono hasło") {
				PasswordModal.hide();
				PasswordModalCom.show();
			}
			else if (response == "Nieprawidłowe hasło!") showMessage(".error-password-old", response);
			else if (response == "Hasła są różne!") showMessage(".error-password1", response);
			else if (response == "Uzupełnij pole!1") {
				response = "Uzupełnij pole!";
				showMessage(".error-password-old", response);
			} 
			else if (response == "Uzupełnij pole!2") {
				response = "Uzupełnij pole!";
				showMessage(".error-password1", response);
			} 
			else if (response == "Uzupełnij pole!3") {
				response = "Uzupełnij pole!";
				showMessage(".error-password2", response);
			} 
		} else {
		}
	};
	xhr.send(formData);
}

function showMessage(place, message) {

	const success = document.querySelector(place);
	success.innerHTML = message;
}

function clearError() {
	var errorPasswordOld = document.querySelector('.error-password-old');
	var errorPassword1 = document.querySelector('.error-password1');
	var errorPassword2 = document.querySelector('.error-password2');
	errorPasswordOld.innerHTML = '';
	errorPassword1.innerHTML = '';
	errorPassword2.innerHTML = '';
}

function clearInputs(place) {
	var popupDiv = document.querySelector(place);
	var inputElements = popupDiv.querySelectorAll("input");
	inputElements.forEach(function (input) {
		input.value = "";
	});
}

function clearErrors(place) {
	var popupDiv = document.querySelector(place);
	const errors = popupDiv.querySelectorAll(".error");
	errors.forEach((error) => {
		error.textContent = "";
	});
}

const addEmployee = document.querySelector(".employee-button");
var wrapper = document.querySelector(".wrapper");
var tooltip = document.querySelector(".tooltip");

if (addEmployee.hasAttribute("disabled")) {
	var divWrapper = document.createElement("div");
	divWrapper.className = "wrap";
	wrapper.parentNode.insertBefore(divWrapper, wrapper);
	divWrapper.appendChild(wrapper);
}

var originalPopupContent = document.querySelector(".modal-body").innerHTML;
function defaultContent() {
	var passwordChange = document.querySelector('.modal-body');

	passwordChange.innerHTML = originalPopupContent;
}

function trimInput(inputValue) {
	var trimmedValue = inputValue.trim();
	return trimmedValue;
}

//zezwala na spacje
function containsOnlyLetters(inputValue, allowNumbers) {
	if (allowNumbers)
		return /^[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż0-9.\s-]*$/.test(inputValue);
	else return /^[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż\s-]*$/.test(inputValue); //true - same dozwolone znaki
}

function containsOnlyNumbers(inputValue, allowLetters) {
	if (allowLetters)
		return /^(?=.*\d)[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż0-9/]*$/.test(inputValue);
	else return /^(?=.*\d)[0-9/]*$/.test(inputValue); //true - przynajmniej jedna cyfra
}

function capitalizeFirstLetter(inputValue) {
	if (inputValue.length > 0) {
		const words = inputValue.split(/[\s-]+/);
		for (let i = 0; i < words.length; i++) {
			words[i] =
				words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
		}
		return words.join("-");
	} else {
		return inputValue;
	}
}

function zipCodeCorrect(inputValue) {
	var regex = /^\d{2}-\d{3}$/;
	return regex.test(inputValue);
}

function emailCorrect(inputValue) {
	const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	return regex.test(inputValue);
}

function phoneNumberCorrect(inputValue) {
	const regex = /^(\d{9}|\d{3}-\d{3}-\d{3})$/;
	 return regex.test(inputValue);
}

function dateCorrect(startDate, endDate) {
	var startDate = new Date(startDate);
	var endDate = new Date(endDate);

	if (startDate < endDate) return true;
	else return false;
}

function validation(name, surname, sex, birthDate, street, houseNumber, city, zipCode, email, phoneNumber, position, hireDate,  nameError, surnameError, birthDateError, streetError, houseNumberError, cityError, zipCodeError, emailError, phoneNumberError, hireDateError) {
		
	//flagi do walidacji
	var validName = true;
	var validSurname = true;
	var validSex = true;
	var validBirthDate = true;
	var validStreet = true;
	var validHouseNumber = true;
	var validCity = true;
	var validZipCode = true;
	var validEmail = true;
	var validPhoneNumber = true;
	var validPosition = true;
	var validHireDate = true;

	//walidacja imie - dozwolone litery, '-'
	name = trimInput(name);
	if (name.trim() !== "") {
		if (!containsOnlyLetters(name, false)) {
			nameError.innerText = "Niepoprawnie wprowadzone dane.";
			nameError.style.display = "block";
			validName = false;
		} else {
			var resultName = capitalizeFirstLetter(name);
			nameError.style.display = "none";
			name = resultName;
			console.log(name);
		}
	} else {
		console.log("Pusty input.");
		nameError.innerText = "Uzupełnij pole!";
		nameError.style.display = "block";
		validName = false;
	}

	//walidacja nazwisko - dozwolone litery, '-'
	surname = trimInput(surname);
	if (surname.trim() !== "") {
		if (!containsOnlyLetters(surname, false)) {
			surnameError.innerText = "Niepoprawnie wprowadzone dane.";
			surnameError.style.display = "block";
			validSurname = false;
		} else {
			var resultSurname = capitalizeFirstLetter(surname);
			surnameError.style.display = "none";
			surname = resultSurname;
			console.log(surname);
		}
	} else {
		console.log("Pusty input.");
		surnameError.innerText = "Uzupełnij pole!";
		surnameError.style.display = "block";
		validSurname = false;
	}

	//walidacja data urodzenia
	if (!birthDate) {
		//po prostu czy nie jest pusta data
		console.log("Pusty input.");
		birthDateError.innerText = "Uzupełnij pole!";
		birthDateError.style.display = "block";
		validBirthDate = false;
	} else birthDateError.style.display = "none";

	//walidacja ulica - dozwolone litery, '-', '/', '.', spacje, numery
	street = trimInput(street);
	if (street.trim() !== "") {
		console.log(street);
		if (!containsOnlyLetters(street, true)) {
			streetError.innerText = "Niepoprawnie wprowadzone dane.";
			streetError.style.display = "block";
			validStreet = false;
		} else {
			var resultStreet = capitalizeFirstLetter(street);
			streetError.style.display = "none";
			street = resultStreet;
			console.log(street);
		}
	} else {
		console.log("Pusty input.");
		streetError.innerText = "Uzupełnij pole!";
		streetError.style.display = "block";
		validStreet = false;
	}

	//walidacja numer domu - dozwolone litery, numery, '/'
	houseNumber = trimInput(houseNumber);
	if (houseNumber.trim() !== "") {
		if (!containsOnlyNumbers(houseNumber, true)) {
			houseNumberError.innerText = "Niepoprawnie wprowadzone dane.";
			houseNumberError.style.display = "block";
			validHouseNumber = false;
		} else {
			var resultHouseNumber = capitalizeFirstLetter(houseNumber);
			houseNumberError.style.display = "none";
			houseNumber = resultHouseNumber;
		}
	} else {
		console.log("Pusty input.");
		houseNumberError.innerText = "Uzupełnij pole!";
		houseNumberError.style.display = "block";
		validHouseNumber = false;
	}

	//walidacja miasto - dozwolone litery, '-'
	city = trimInput(city);
	if (city.trim() !== "") {
		console.log(city);
		if (!containsOnlyLetters(city, false)) {
			cityError.innerText = "Niepoprawnie wprowadzone dane.";
			cityError.style.display = "block";
			validCity = false;
		} else {
			var resultCity = capitalizeFirstLetter(city);
			cityError.style.display = "none";
			city = resultCity;
			console.log(city);
		}
	} else {
		console.log("Pusty input.");
		cityError.innerText = "Uzupełnij pole!";
		cityError.style.display = "block";
		validCity = false;
	}

	//walidacja kod pocztowy - format XX-XXX
	zipCode = trimInput(zipCode);
	if (zipCode.trim() !== "") {
		console.log(zipCode);
		if (!zipCodeCorrect(zipCode)) {
			zipCodeError.innerText = "Niepoprawnie wprowadzone dane.";
			zipCodeError.style.display = "block";
			validZipCode = false;
		} else {
			var resultZipCode = capitalizeFirstLetter(zipCode);
			zipCodeError.style.display = "none";
			zipCode = resultZipCode;
			console.log(zipCode);
		}
	} else {
		console.log("Pusty input.");
		zipCodeError.innerText = "Uzupełnij pole!";
		zipCodeError.style.display = "block";
		validZipCode = false;
	}

	//walidacja email - czy dobry format
	email = trimInput(email);
	if (email.trim() !== "") {
		console.log(email);
		if (!emailCorrect(email)) {
			emailError.innerText = "Niepoprawnie wprowadzone dane.";
			emailError.style.display = "block";
			validEmail = false;
		} else {
			emailError.style.display = "none";
			console.log(email);
		}
	} else {
		console.log("Pusty input.");
		emailError.innerText = "Uzupełnij pole!";
		emailError.style.display = "block";
		validEmail = false;
	}

	//walidacja numer telefonu
	//dozwolone formatyL XXXXXXXXX, XXX-XXX-XXX
	phoneNumber = trimInput(phoneNumber);
	if (phoneNumber.trim() !== "") {
		console.log(phoneNumber);
		if (!phoneNumberCorrect(phoneNumber)) {
			phoneNumberError.innerText = "Niepoprawnie wprowadzone dane.";
			phoneNumberError.style.display = "block";
			validPhoneNumber = false;
		} else {
			phoneNumberError.style.display = "none";
			console.log(phoneNumber);
		}
	} else {
		console.log("Pusty input.");
		phoneNumberError.innerText = "Uzupełnij pole!";
		phoneNumberError.style.display = "block";
		validPhoneNumber = false;
	}


	//walidacja data zatrudnienia - czy pozniejsza niz birthDate
	if (!hireDate) {
		//po prostu czy nie jest pusta data
		console.log("Pusty input.");
		hireDateError.innerText = "Uzupełnij pole!";
		hireDateError.style.display = "block";
		validHireDate = false;
	} else {
		hireDateError.style.display = "none";
		if (!dateCorrect(birthDate, hireDate)) {
			hireDateError.innerText =
				"Data zatrudnienia nie może być wcześniejsza niż data urodzenia!";
			hireDateError.style.display = "block";
			validHireDate = false;
		}
	}

	console.log("Imie: " + validName);
	console.log("Nazwisko: " + validSurname);
	console.log("Płeć: " + validSex);
	console.log("Data urodzenia: " + validBirthDate);
	console.log("Ulica: " + validStreet);
	console.log("Numer domu: " + validHouseNumber);
	console.log("Miasto: " + validCity);
	console.log("Kod pocztowy: " + validZipCode);
	console.log("Email: " + validEmail);
	console.log("Number telefonu: " + validPhoneNumber);
	console.log("Stanowisko: " + validPosition);
	console.log("Data zatrudnienia: " + validHireDate);

	if (
		validName &&
		validSurname &&
		validSex &&
		validBirthDate &&
		validStreet &&
		validHouseNumber &&
		validCity &&
		validZipCode &&
		validEmail &&
		validPhoneNumber &&
		validPosition && 
		validHireDate
	) {
		return {
			isValid: true,
			name: name,
			surname: surname,
			sex: sex,
			birthDate: birthDate,
			street: street,
			houseNumber: houseNumber,
			city: city,
			zipCode: zipCode,
			email: email,
			phoneNumber: phoneNumber,
			position: position,
			hireDate: hireDate,
		};
	} else {
		return { isValid: false };
	}
}

function addNewEmployee() {
	// Pobierz dane z formularza
	console.log("test");
	var name = document.querySelector('input[name="name_input"]').value;
	console.log(name);
	var surname = document.querySelector('input[name="surname_input"]').value;
	console.log(surname);
	var sex = document.querySelector(".sex_input").value;
	console.log(sex);
	var birthDate = document.querySelector('input[name="birth_date_input"]').value;
	console.log(birthDate);
	var street = document.querySelector('input[name="street_input"]').value;
	console.log(street);
	var houseNumber = document.querySelector('input[name="house_number_input"]').value;
	console.log(houseNumber);
	var city = document.querySelector('input[name="city_input"]').value;
	console.log(city);
	var zipCode = document.querySelector('input[name="zip_code_input"]').value;
	console.log(zipCode);
	var email = document.querySelector('input[name="email_input"]').value;
	console.log(email);
	var phoneNumber = document.querySelector('input[name="phone_number_input"]').value;
	console.log(phoneNumber);
	var position = document.querySelector(".position_input").value;
	console.log(position);
	var hireDate = document.querySelector('input[name="hire_date_input"]').value;
	console.log(hireDate);

	//pobranie spanow na bledy w formularzu //poza płcią i czynem zabronionym bo sa tam domyslenie ustawione - nie ma szans na "błąd"
	var nameError = document.getElementById("name-error");
	var surnameError = document.getElementById("surname-error");
	var birthDateError = document.getElementById("birth_date-error");
	var streetError = document.getElementById("street-error");
	var houseNumberError = document.getElementById("house_number-error");
	var cityError = document.getElementById("city-error");
	var zipCodeError = document.getElementById("zip_code-error");
	var emailError = document.getElementById("email-error");
	var phoneNumberError = document.getElementById("phone_number-error");
	var hireDateError = document.getElementById("hire_date-error");

	var validationResult = validation(name, surname, sex, birthDate, street, houseNumber, city, zipCode, email, phoneNumber, position, hireDate, nameError, surnameError, birthDateError, streetError, houseNumberError, cityError, zipCodeError, emailError, phoneNumberError, hireDateError);

	console.log(validationResult.isValid);

	if (validationResult.isValid) {
		// Wysyłanie danych na serwer
		var formData = new FormData();
		formData.append("name", validationResult.name);
		formData.append("surname", validationResult.surname);
		formData.append("sex", validationResult.sex);
		formData.append("birthDate", validationResult.birthDate);
		formData.append("street", validationResult.street);
		formData.append("houseNumber", validationResult.houseNumber);
		formData.append("city", validationResult.city);
		formData.append("zipCode", validationResult.zipCode);
		formData.append("email", validationResult.email);
		formData.append("phoneNumber", validationResult.phoneNumber);
		formData.append("position", validationResult.position);
		formData.append("hireDate", validationResult.hireDate);
		
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "add_employee_to_database.php", true);

		xhr.onload = function () {
			if (xhr.status >= 200 && xhr.status < 300) {
				var response = xhr.responseText;
				employeeModal.hide();
				showMessage(".message-employee", response);
				EmployeeModalCom.show();
			} else {
				//console.error("Błąd podczas wysyłania żądania.");
			}
		};
		xhr.send(formData);
	}
}

const employeeSubmit = document.querySelector(".add-employee");
employeeSubmit.addEventListener("click", addNewEmployee);

document.addEventListener("DOMContentLoaded", ()=> {

	const table = document.querySelector(".my-table");
	const dataRows = table.querySelectorAll("tr");
	const headerRow = table.querySelector("tr");
	
	const table1 = document.querySelector(".my-table1");
	const dataRows1 = table1.querySelectorAll("tr");
	const headerRow1 = table1.querySelector("tr");
	
	const newHeaderCell = document.createElement("th");
	
	newHeaderCell.textContent = "Zarządzaj";
	
	headerRow.appendChild(newHeaderCell);
	
	dataRows.forEach((row, index) => {
		if (index !== 0) {
			const allNumber = document.createElement("td");
			allNumber.textContent = `${index}.`;
			// Pominięcie pierwszego wiersza (nagłówka)
			const newColumn = document.createElement("td");
			newColumn.innerHTML =
				'<div class="d-flex flex-column flex-md-row"><button class="delete-employee">Usuń</button></div>';
			row.appendChild(newColumn);
			row.insertBefore(allNumber, row.firstChild);
	
			const deleteEmployeeButtons = newColumn.querySelectorAll(".delete-employee");
	
			deleteEmployeeButtons.forEach((button) => {
				const row = button.closest("tr");
				const employeeId = row.querySelector(".id_data").textContent;
				//button.setAttribute("data-id", employeeId);
				console.log(employeeId);
	
				button.addEventListener("click", () => {
					//deleteEmployee(employeeId);
					console.log(employeeId);
					deleteButton.setAttribute("data-id", employeeId);
					var response = "Jesteś pewny, że chcesz usunąć pracownika o ID: " + employeeId + "?";
					employeeListModal.hide();
					showMessage(".message-delete", response);
					deleteModal.show();
				});
				
			});
		}
	});
	
	dataRows1.forEach((row, index) => {
		if (index !== 0) {
			const allNumber = document.createElement("td");
			allNumber.textContent = `${index}.`;
			// Pominięcie pierwszego wiersza (nagłówka)
			
			row.insertBefore(allNumber, row.firstChild);
	
		}
	});

})



const deleteButton = document.querySelector('.delete-submit');
deleteButton.addEventListener("click", deleteEmployee);

function deleteEmployee() {
	//const buttons = document.querySelectorAll(".delete-employee");
	//buttons.forEach((button) => {
		const employeeId = deleteButton.getAttribute("data-id");
		console.log(employeeId);

		var formData = new FormData();
		formData.append("employeeId", employeeId);

		var xhr = new XMLHttpRequest();
		xhr.open("POST", "remove_employee.php", true);

		xhr.onload = function () {
			if (xhr.status >= 200 && xhr.status < 300) {
				var response = xhr.responseText;
				deleteModal.hide();
				console.log(response);
				showMessage(".message-employee", response);
				EmployeeModalCom.show();
			} else {
				//console.error("Błąd podczas wysyłania żądania.");
			}
		};
		xhr.send(formData);
	//});
}



	

