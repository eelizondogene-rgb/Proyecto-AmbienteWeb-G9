document.addEventListener("DOMContentLoaded", function () {
    let statNumbers = document.querySelectorAll(".stat-number");
    for (let i = 0; i < statNumbers.length; i++) {
        let target = parseInt(statNumbers[i].innerText);
        let current = 0;
        let increment = Math.ceil(target / 30);
        let elemento = statNumbers[i];
        let counter = setInterval(function () {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(counter);
            }
            elemento.innerText = current;
        }, 40);
    }
});