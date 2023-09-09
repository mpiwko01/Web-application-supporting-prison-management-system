const Cells = document.querySelectorAll(".prison_cell");

const IsCellTaken = () => {
	Cells.forEach((item) => {
		const PrisonerSpan = item.querySelector(".prisoner");

		if (PrisonerSpan && PrisonerSpan.textContent.trim() == "") {
			item.style.backgroundColor = "#a3d7a3";
		} else {
			item.style.background = "red";
		}
	});
};

IsCellTaken();
