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

//sendBtn.addEventListener("click", (e) => {
	//e.preventDefault();

	//checkForm([IDLabel, pass]);
//});

clearBtn.addEventListener("click", (e) => {
	//e.preventDefault();

	[IDLabel, pass].forEach((el) => {
		el.value = "";
		clearError(el);
	});
});
