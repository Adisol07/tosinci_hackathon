const btn = document.getElementById("napovedaBtn");
const text = document.getElementById("napovedaText");

btn.addEventListener("click", () => {
    if (text.style.display === "none") {
        text.style.display = "block";
    } else {
        text.style.display = "none";
    }
});






