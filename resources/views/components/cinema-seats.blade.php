<div class="cinema-wrapper mt-5">
    <h1>Disposition des sièges 🎬</h1>
    <div class="screen">ÉCRAN GÉANT</div>
    <div class="cinema" id="cinema-{{ $event->id }}"></div>

    <div class="legend mt-4">
        <h5 class="text-light">Légende des sièges</h5>
        <ul class="list-unstyled d-flex gap-4">
            <li><span class="legend-box free"></span> Libre</li>
            <li><span class="legend-box selected"></span> Mes sièges</li>
            <li><span class="legend-box reserved"></span> Occupé</li>
        </ul>
    </div>

    <style>
        .legend-box { display:inline-block;width:20px;height:20px;border-radius:4px;margin-right:8px;border:2px solid #333; }
        .legend-box.free { background: limegreen; }
        .legend-box.selected { background: crimson; }
        .legend-box.reserved { background: gray; }
        .cinema { position: relative; width: 100%; min-height: 600px; margin-top: 30px; }
        .seat { width: 22px; height: 22px; border-radius: 50%; position: absolute; border: 2px solid #333; }
        .seat.free { background: limegreen; }
        .seat.selected { background: crimson; }
        .seat.reserved { background: gray; }
        .screen { width: 60%; height: 30px; background: silver; margin: 0 auto; border-radius: 5px; text-align: center; line-height: 30px; font-weight: bold; }
    </style>

    <script>
        (function() {
            const cinema = document.getElementById("cinema-{{ $event->id }}");

            const mySeats = @json($mySeats ?? []);
            const reservedSeats = @json($reservedSeats ?? []);

            const niveaux = 10, baseSeats = 30, increment = 2;
            const centerX = cinema.offsetWidth / 2, centerY = 600;
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
                    seat.disabled = true;

                    const angle = startAngle + (s * angleStep);
                    const x = centerX + radius * Math.cos(angle);
                    const y = centerY - radius * Math.sin(angle);

                    seat.style.left = (x - 11) + "px";
                    seat.style.top = (y - 11) + "px";

                    if (mySeats.includes(seat.id)) {
                        seat.checked = true;
                        seat.classList.add("selected");
                    } else if (reservedSeats.includes(seat.id)) {
                        seat.checked = true;
                        seat.classList.add("reserved");
                    } else {
                        seat.classList.add("free");
                    }

                    cinema.appendChild(seat);
                }
            }
        })();
    </script>
</div>
