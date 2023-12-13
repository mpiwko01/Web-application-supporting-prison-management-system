const IDLabel = document.querySelector(".id");
const pass = document.querySelector(".password");

const sendBtn = document.querySelector(".submit-btn");
const clearBtn = document.querySelector(".clear-btn");

const showError = (input, msg) => {
	const formBox = input.parentElement;
	const errorMsg = formBox.querySelector(".error-text");

	formBox.classList.add("error");
	errorMsg.style.display = "flex";
	errorMsg.textContent = msg;
};

const clearError = (input) => {
	const formBox = input.parentElement;
	const errorMsg = formBox.querySelector(".error-text");
	formBox.classList.remove("error");

	errorMsg.style.display = "none";
};

const checkForm = (input) => {
	input.forEach((el) => {
		if (el.value === "") {
			showError(el, `Uzupełnij ${el.placeholder}!`);
		} else {
			clearError(el);
		}
	});
};

clearBtn.addEventListener("click", (e) => {

	[IDLabel, pass].forEach((el) => {
		el.value = "";
		clearError(el);
	});
});

document.addEventListener("DOMContentLoaded", function() {
    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'update_database.php', true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var response = xhr.responseText;
            console.log('Update database');
        } else {
            console.error('Błąd podczas pobierania danych.');
        }
    };
    
    xhr.send();
});





