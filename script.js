function loadServerStatus() {
    fetch('php/server_status.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('cpu').innerText = `CPU Load: ${data.cpuLoad}`;
            document.getElementById('ram').innerText = `RAM Usage: ${data.ramUsed}%`;
            document.getElementById('disk').innerText = `Disk Usage: ${data.diskUsed}%`;
            document.getElementById('apache').innerText = `Apache: ${data.apache}`;
            document.getElementById('mysql').innerText = `MySQL: ${data.mysql}`;
        });
}

// Refresh setiap 5 detik
setInterval(loadServerStatus, 5000);
window.onload = loadServerStatus;
