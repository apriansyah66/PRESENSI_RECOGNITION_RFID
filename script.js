let lastUID = '';

function fetchData() {
    fetch("get_rfid_data.php")
        .then(response => response.json())
        .then(data => {
            const waktu = new Date().toLocaleString('id-ID');
            document.getElementById("waktu").textContent = waktu;
            document.getElementById("rfid").textContent = data.rfid_uid || '-';
            document.getElementById("nim").textContent = data.nim || '-';
            document.getElementById("nama").textContent = data.nama || '-';
            document.getElementById("prodi").textContent = data.prodi || '-';

            const absenDiv = document.getElementById("absen");
            absenDiv.innerHTML = "";
            if (data.absensi && Array.isArray(data.absensi)) {
                data.absensi.forEach(item => {
                    const p = document.createElement("p");
                    p.textContent = item;
                    absenDiv.appendChild(p);
                });
            }

            const statusEl = document.getElementById("status");
            if (data.status === "unknown") {
                statusEl.classList.add("warning");
                statusEl.textContent = "❌ RFID tidak dikenal. Silakan daftarkan terlebih dahulu.";
            } else if (data.rfid_uid && data.rfid_uid !== lastUID) {
                statusEl.classList.remove("warning");
                statusEl.textContent = "✅ Kartu baru terdeteksi!";
                lastUID = data.rfid_uid;
                setTimeout(() => {
                    statusEl.textContent = "";
                }, 3000);
            }

            // Tampilkan foto wajah jika ada
            const foto = data.foto_wajah;
            const fotoEl = document.getElementById("foto_wajah");
            if (foto) {
                fotoEl.src = foto;
                fotoEl.style.display = "block";
            } else {
                fotoEl.style.display = "none";
            }
        })
        .catch(error => {
            console.error("Gagal ambil data:", error);
        });
}

setInterval(fetchData, 2000);
fetchData();
