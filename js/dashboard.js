// coversion modal form
// const convertBtn = document.getElementById("convertBtn");
// const closeConversion = document.getElementById("close-conversion");

// convertBtn.addEventListener("click", () => {
//     document.getElementById("conversion-modal").style.display = "block";
// });

// closeConversion.addEventListener("click", () => {
//     document.getElementById("conversion-modal").style.display = "none";
// });

export function openConvertModal() {
    backdrop.style.display = "block";
    document.getElementById("conversion-modal").style.display = "block";
}

export function closeConvertModal() {
    backdrop.style.display = "none";
    document.getElementById("conversion-modal").style.display = "none";
}
