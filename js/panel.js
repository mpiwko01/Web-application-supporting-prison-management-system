const PasswordModal = new bootstrap.Modal(
	document.getElementById("password_modal")
);

const PasswordModalCom = new bootstrap.Modal(
	document.getElementById("password_modal_com")
);

const passwordButton = document.querySelector('.password-button');

passwordButton.addEventListener("click", function() {
	PasswordModal.show();
});

const closeButton = document.querySelector('.btn-close');

closeButton.addEventListener("click", () => {
	PasswordModal.hide();
	PasswordModalCom.hide();
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

var originalPopupContent = document.querySelector(".modal-body").innerHTML;

function clearError() {
	var errorPasswordOld = document.querySelector('.error-password-old');
	var errorPassword1 = document.querySelector('.error-password1');
	var errorPassword2 = document.querySelector('.error-password2');
	errorPasswordOld.innerHTML = '';
	errorPassword1.innerHTML = '';
	errorPassword2.innerHTML = '';
}

function defaultContent() {
	var passwordChange = document.querySelector('.modal-body');

	passwordChange.innerHTML = originalPopupContent;
}







	

