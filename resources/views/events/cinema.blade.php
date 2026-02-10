<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Salle de cinéma en arc réel</title>
  <style>
    body {
      background: #111;
      color: #fff;
      font-family: Arial, sans-serif;
      text-align: center;
      margin: 0;
      padding: 0;
    }

    .cinema {
      position: relative;
      width: 100%;
      height: 100%;
      margin-top: 30px;
    }

    .seat {
      appearance: none;
      -webkit-appearance: none;
      width: 22px;
      height: 22px;
      background: crimson;
      border-radius: 50%;
      position: absolute;
      transition: 0.3s;
      border: 2px solid #333;
    }

    .seat:hover {
      background: gold;
      cursor: pointer;
    }

    .seat:checked {
      background: limegreen;
    }

    .screen {
      width: 60%;
      height: 30px;
      background: silver;
      margin: 0 auto;
      border-radius: 5px;
    }

    h1 {
      margin: 15px 0;
    }

    #info {
      margin-top: 700px;
      font-size: 18px;
      color: gold;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    li {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="body">
  <h1>Disposition des sièges 🎬</h1>
  <div class="screen">ÉCRAN GÉANT</div>
  <div class="cinema" id="cinema"></div>
  <div id="info">
  <strong>Sièges sélectionnés :</strong>
  <ul id="selectedSeats"></ul>
</div>
</div>  

<button id="validateSeats" class="btn btn-success mt-3">Valider la sélection</button>


<script>
  const cinema = document.getElementById("cinema");
  const selectedSeatsList = document.getElementById("selectedSeats");
  const validateBtn = document.getElementById("validateSeats");
  let selectedSeats = [];

  function updateList(seat) {
    const seatId = seat.id;
    if (seat.checked) {
      selectedSeats.push(seatId);
      const li = document.createElement("li");
      li.textContent = seatId;
      li.id = "li-" + seatId;
      selectedSeatsList.appendChild(li);
    } else {
      selectedSeats = selectedSeats.filter(id => id !== seatId);
      const liToRemove = document.getElementById("li-" + seatId);
      if (liToRemove) selectedSeatsList.removeChild(liToRemove);
    }
  }

  document.getElementById("validateSeats").addEventListener("click", () => { if (selectedSeats.length === 0) { 
    alert("Veuillez sélectionner au moins un siège !");
   return; } 
  // redirection vers show.blade.php avec les sièges en paramètre GET 
  const seatsParam = encodeURIComponent(selectedSeats.join(","));
   window.location.href = "/events/{{ $event->id }}?seats=" + seatsParam; });

  // Génération des sièges
  const niveaux = 10, baseSeats = 30, increment = 2;
  const centerX = window.innerWidth / 2, centerY = 550;
  const arcAngle = 0.35 * 2 * Math.PI, seatSpacing = 1.2;

  for (let n = 0; n < niveaux; n++) {
    const seatsCount = baseSeats + (n * increment);
    const radius = 180 + (n * 45);
    const angleStep = (arcAngle * seatSpacing) / (seatsCount - 1);
    const startAngle = Math.PI/2 - (arcAngle * seatSpacing)/2;

    for (let s = 0; s < seatsCount; s++) {
      const seat = document.createElement("input");
      seat.type = "checkbox";
      seat.classList.add("seat");
      seat.id = `seat-${n + 1}-${s + 1}`;
      seat.dataset.row = n + 1;
      seat.dataset.number = s + 1;

      const angle = startAngle + (s * angleStep);
      const x = centerX + radius * Math.cos(angle);
      const y = centerY - radius * Math.sin(angle);

      seat.style.left = (x - 11) + "px";
      seat.style.top = (y - 11) + "px";

      seat.addEventListener("change", () => updateList(seat));
      cinema.appendChild(seat);
    }
  }

  // ✅ Envoi vers le formulaire
  validateBtn.addEventListener("click", () => {
    const seatsInput = document.querySelector("input[name='seats']");
    if (seatsInput) {
      seatsInput.value = selectedSeats.join(", ");
      alert("Sélection validée : " + seatsInput.value);
    }
  });
</script>

</body>
</html>
   