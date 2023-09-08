const Cells = document.querySelectorAll(".prison_cell");

const IsCellTaken = () => {
	Cells.forEach((item) => {
		const PrisonerSpan = item.querySelector(".prisoner");

		if (PrisonerSpan && PrisonerSpan.textContent.trim() == "") {
			item.style.background = "green";
		} else {
			item.style.background = "red";
		}
	});
};

IsCellTaken();
