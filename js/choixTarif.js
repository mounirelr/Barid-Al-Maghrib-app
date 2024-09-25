document.addEventListener("DOMContentLoaded", function () {
  var getPriceButton = document.getElementById("getPrice");

  getPriceButton.addEventListener("click", function (event) {
    event.preventDefault();

    var weightInput = document.getElementById("weightInput").value;
    if (weightInput.length > 0 && weightInput > 0) {
      var xhr = new XMLHttpRequest();

      xhr.open("POST", "/poste/Envoi/getPrice.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      xhr.send("weight=" + weightInput);

      xhr.onload = function () {
        if (xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);

          var priceResult = document.getElementById("priceResult");
          getPriceButton.style.display = "none";

          if (
            response.error &&
            response.error === "No prices found for the provided weight"
          ) {
            priceResult.innerHTML = `
                        <h2>Aucun tarif trouvé</h2>
                        <button id="resetButton">Réinitialiser</button>
                    `;
            priceResult.style.display = "block";

            var resetButton = document.getElementById("resetButton");
            resetButton.addEventListener("click", function () {
              priceResult.innerHTML = "";
              priceResult.style.display = "none";
              document.getElementById("weightInput").value='';

              getPriceButton.style.display = "block";
            });
          } else {
            if (response.length == 1) {
              priceResult.innerHTML = `
                        <h2>Tarif:</h2>
                        <button class="choice">Colis : ${response[0]} DH</button>
                    `;
            } else {
              priceResult.innerHTML = `
                        <h2>Tarif:</h2>
                        <button class="choice">Colis : ${response[0]} DH</button>
                        <button class="choice">Courrier : ${response[1]} DH</button>
                    `;
            }
            priceResult.style.display = "block";

            let buttonChoice = document.querySelectorAll(".choice");
            console.log(buttonChoice);
            buttonChoice.forEach(function (button) {
              button.onclick = function () {
                var priceCalculator =
                  document.querySelector(".price_calculator");
                var buttonContent = button.textContent;
                var typeSend = buttonContent.slice(
                  0,
                  buttonContent.indexOf(":") - 1
                );
                var priceMatch = buttonContent.match(/\d+/);
                var price = priceMatch ? priceMatch[0] : "";
                if (priceCalculator) {
                  priceCalculator.remove();
                }

                window.location.href = `ajouterEnvoi.php?weight=${weightInput}&type=${typeSend}&tarif=${price}`;
              };
            });
          }
        } else {
          console.error("Request failed. Status: " + xhr.status);
        }
      };

      xhr.onerror = function () {
        console.error("Network error.");
      };
    }
  });
});
