const backdrop = document.getElementById("backdrop");
const menuIcon = document.querySelector(".menu-icon");
const mobileMenu = document.querySelector(".mobile-menu");
const closeMobileMenu = document.querySelector(".mobile-menu button");

menuIcon.addEventListener("click", () => {
    mobileMenu.classList.add("show");
});

closeMobileMenu.addEventListener("click", () => {
    mobileMenu.classList.remove("show");
});

function loadPage(page) {
    fetch(`${page}.html`)
        .then(response => response.text())
        .then(data => {
            // for mobile mode only
            mobileMenu.classList.remove("show");
            document.getElementById("main-content").innerHTML = data;
        })
        .catch(error => console.error("Error loading page:", error));
}

function openConvertModal() {
    backdrop.style.display = "block";
    document.getElementById("conversion-modal").style.display = "block";
}

function closeConvertModal() {
    backdrop.style.display = "none";
    document.getElementById("conversion-modal").style.display = "none";
}

const handleInputChange = e => {
    let inputValue = e.target.value;

    const decimalValue = inputValue
        .replace(/[^\d.]/g, "") // Allow only digits and decimal point
        .replace(/^(\d*\.\d*)\./, "$1") // Remove multiple decimal points
        .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") // Add commas
        .replace(/(\.\d*)(,)/g, "$1");

    if (e.target.name === "convert") {
        e.target.value = decimalValue;
    }

    if (e.target.name === "to") {
        e.target.value = decimalValue;
    }
};

const submitFormValues = () => {
    const fromCurrency = document.getElementById("convert");
    const toCurrency = document.getElementById("to");

    if (!fromCurrency.value && !toCurrency.value) {
        alert("Please enter input values!");
        return;
    }

    alert(
        `Naira value: ${fromCurrency.value} Dollar value: ${toCurrency.value}`
    );

    console.log(
        `Naira value: ${fromCurrency.value} Dollar value: ${toCurrency.value}`
    );
};
