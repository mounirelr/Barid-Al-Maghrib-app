let buttonCheck = document.querySelector("#checkUser");

buttonCheck.onclick = () => {
  event.preventDefault();
  cinClient = document.getElementById("cin").value;

  const xhr = new XMLHttpRequest();

  xhr.open("POST", "/poste/Envoi/checkUser.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.send("cin=" + cinClient);

  xhr.onload = function () {
    if (xhr.status == 200) {
      console.log(xhr.responseText);
      var response = JSON.parse(xhr.responseText);
      if (!response.error) {
        firstName = document.getElementById("firstName").value =
          response.firstName;
        lastName = document.getElementById("lastName").value =
          response.lastName;
        city = document.getElementById("city").value = response.city;
        adress = document.getElementById("adress").value = response.adress;
        phone = document.getElementById("phone").value = response.phone;
        console.log("done");
      } else {
        function displayMessageExist() {
          var MessageExist = document.getElementById("MessageExist");
          if (MessageExist.style.display == "none")
            MessageExist.style.display = "block";
          else MessageExist.style.display = "none";
        }
        displayMessageExist();
        setTimeout(displayMessageExist, 3000);
        firstName = document.getElementById("firstName").value = "";
        lastName = document.getElementById("lastName").value = "";
        city = document.getElementById("city").value = "";
        adress = document.getElementById("adress").value = "";
        phone = document.getElementById("phone").value = "";
      }
    }
  };
};
