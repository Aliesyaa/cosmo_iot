<?php include 'menu.php'; ?>

<?php
$boxId = $_GET['box'] ?? '';
$country = $_GET['country'] ?? 'Unknown';
$apiUrl = "https://api.opensensemap.org/boxes/$boxId";
?>

<div class="card">
    <h2>🌍 Sensor Data – <?= htmlspecialchars($country) ?></h2>
    <p class="countdown">Refreshing in <span id="countdown">15</span> seconds...</p>
    <div id="map" class="map"></div>
</div>

<div id="sensorContainer"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let map, marker;
    let countdown = 15;
    let charts = {};

    function updateCountdown() {
        document.getElementById('countdown').innerText = countdown;
        countdown--;
        if (countdown < 0) {
            countdown = 15;
            fetchData();
        }
    }

    setInterval(updateCountdown, 1000);

    function fetchData() {
        fetch('<?= $apiUrl ?>')
            .then(res => res.json())
            .then(data => {
                const coords = data.currentLocation.coordinates;
                const sensors = data.sensors;
                const container = document.getElementById('sensorContainer');
                container.innerHTML = '';

                // Map
                if (!map) {
                    map = L.map('map').setView([coords[1], coords[0]], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    marker = L.marker([coords[1], coords[0]]).addTo(map).bindPopup(data.name).openPopup();
                } else {
                    marker.setLatLng([coords[1], coords[0]]);
                    map.setView([coords[1], coords[0]], 16);
                }

                sensors.forEach(sensor => {
                    if (!sensor.lastMeasurement) return;

                    const id = sensor._id;
                    const title = sensor.title;
                    const unit = sensor.unit;
                    const value = parseFloat(sensor.lastMeasurement.value);
                    const time = new Date(sensor.lastMeasurement.createdAt).toLocaleTimeString();

                    const card = document.createElement('div');
                    card.className = 'card';
                    card.innerHTML = `
                        <h3>${title} (${unit})</h3>
                        <p><strong>Value:</strong> ${value} ${unit}<br>
                        <strong>Time:</strong> ${time}</p>
                        <div class="chart-container">
                            <canvas id="chart_${id}"></canvas>
                        </div>
                    `;
                    container.appendChild(card);

                    const ctx = document.getElementById(`chart_${id}`).getContext('2d');

                    if (!charts[id]) {
                        charts[id] = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: [time],
                                datasets: [{
                                    label: `${title} (${unit})`,
                                    data: [value],
                                    borderColor: getRandomColor(),
                                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: false
                                    }
                                }
                            }
                        });
                    } else {
                        charts[id].data.labels.push(time);
                        charts[id].data.datasets[0].data.push(value);
                        if (charts[id].data.labels.length > 10) {
                            charts[id].data.labels.shift();
                            charts[id].data.datasets[0].data.shift();
                        }
                        charts[id].update();
                    }
                });
            });
    }

    function getRandomColor() {
        return 'hsl(' + Math.floor(Math.random() * 360) + ', 70%, 50%)';
    }

    // Ensure DOM is ready before fetching
    window.onload = fetchData;
</script>

</body>
</html>
