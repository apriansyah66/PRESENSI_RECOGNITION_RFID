<!DOCTYPE html>
<html lang="id">
<head>
 <?php include('sidebarM.HTML'); ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Absensi RFID & Face Recognition</title>
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script defer src="script_face.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
  font-family: Arial, sans-serif;
  text-align: center;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  min-height: 100vh;
  margin: 0;
  padding: 20px;
}
video, canvas {
  border: 2px solid #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.5);
}
.container {
  max-width: 600px;
  margin: 0 auto;
  background: rgba(255,255,255,0.1);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 0 20px rgba(0,0,0,0.3);
}
.card {
  background: #fff;
  color: #333;
  border-radius: 15px;
  padding: 20px;
  margin-top: 20px;
}
button.face-btn {
  margin-top: 20px;
  padding: 12px 20px;
  font-size: 16px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0,0,0,0.2);
}
button.face-btn:hover {
  background-color: #45a049;
  transform: scale(1.05);
}
.video-container {
  position: relative;
  display: inline-block;
}
#faceCanvas {
  position: absolute;
  top: 0;
  left: 0;
  pointer-events: none;
  z-index: 10;
}
.success-card {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
  border-radius: 15px;
  padding: 20px;
  margin-top: 20px;
}
.matkul-selector {
  background: #fff;
  color: #333;
  border-radius: 15px;
  padding: 20px;
  margin-top: 20px;
}
.matkul-selector select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  margin-bottom: 15px;
}
.attendance-status {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  padding: 15px;
  margin-top: 10px;
  text-align: left;
}
.status-hadir { color: #28a745; }
.status-alpha { color: #dc3545; }
.status-izin { color: #ffc107; }
.status-sakit { color: #17a2b8; }
</style>
</head>
<body>
<div class="container">
  <h2>📇 Autentikasi RFID</h2>
  <div id="rfid-section">
    <div id="app-content" class="card">
      <p>🔄 Menunggu kartu RFID ditempel...</p>
    </div>
    <button id="lanjut-face-btn" class="face-btn" style="display:none" onclick="runFaceRecognition()">
  <i class="fas fa-eye"></i> Lanjut ke Face Recognition
</button>
  </div>
  <div id="face-section" style="display:none; margin-top:20px;">
    <h2>👤 Autentikasi Wajah</h2>
    <div class="video-container">
      <video id="video" width="480" height="360" autoplay muted></video>
      <canvas id="faceCanvas" width="480" height="360"></canvas>
    </div>
    <div id="status">📷 Klik Lanjut untuk memulai face recognition...</div>
  </div>
  
  <!-- Section untuk input absensi setelah face recognition berhasil -->
  <div id="attendance-section" style="display:none;">
    <h2>📝 Input Absensi</h2>
    <div class="matkul-selector">
      <h3>Pilih Mata Kuliah:</h3>
      <select id="matkul-select">
        <option value="">-- Pilih Mata Kuliah --</option>
        <option value="algoritma">Algoritma dan Struktur Data</option>
        <option value="pemrograman">Pemrograman Web</option>
        <option value="database">Basis Data</option>
        <option value="jaringan">Jaringan Komputer</option>
        <option value="sistem-operasi">Sistem Operasi</option>
        <option value="mobile">Pemrograman Mobile</option>
        <option value="ai">Kecerdasan Buatan</option>
        <option value="grafika">Grafika Komputer</option>
      </select>
      <button id="submit-absen" class="face-btn" onclick="submitAbsensi()">
        <i class="fas fa-check"></i> Submit Absensi
      </button>
    </div>
    
    <!-- Status absensi berdasarkan mata kuliah -->
    <div id="attendance-history" class="card" style="display:none;">
      <h3>📊 Status Absensi</h3>
      <div id="attendance-details"></div>
    </div>
  </div>
  
  <!-- Section berhasil absen -->
  <div id="success-section" style="display:none;">
    <div class="success-card">
      <h2>🎉 Absensi Berhasil!</h2>
      <div id="success-details"></div>
      <button class="face-btn" onclick="resetSystem()" style="background-color: #007bff; margin-top: 15px;">
        <i class="fas fa-refresh"></i> Absen Lagi
      </button>
    </div>
  </div>
</div>

<script>
class RFID {
  constructor() {
    this.apiUrl = 'absennn.php';
    this.intervalId = null;
    this.currentUser = null;
  }
  start() {
    this.intervalId = setInterval(() => this.checkRFID(), 3000);
  }
  async checkRFID() {
    const content = document.getElementById('app-content');
    try {
      const res = await fetch(this.apiUrl);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      if (data.tampilkan_card) {
        clearInterval(this.intervalId);
        this.currentUser = data; // Simpan data user untuk digunakan nanti
        content.innerHTML = `
          <div>
            <p><strong>Nama:</strong> ${data.nama}</p>
            <p><strong>NIM:</strong> ${data.nim}</p>
            <p><strong>Prodi:</strong> ${data.prodi}</p>
            <p><strong>RFID:</strong> ${data.rfid_uid}</p>
            <p><strong>Waktu:</strong> ${data.waktu}</p>
            <p style="color:green;font-weight:bold;">🎉 RFID Terverifikasi!</p>
          </div>
        `;
        document.getElementById('lanjut-face-btn').style.display = 'inline-block';
      } else if (data.rfid_tidak_dikenal) {
        content.innerHTML = `
          <div>
            <p style="color:red;font-weight:bold;">🚫 Kartu tidak dikenal</p>
            <p><strong>UID:</strong> ${data.rfid_uid}</p>
            <p>Hubungi admin untuk pendaftaran.</p>
          </div>
        `;
      } else {
        content.innerHTML = `<p>🔄 Menunggu kartu RFID ditempel...</p>`;
      }
    } catch (err) {
      content.innerHTML = `<p style="color:red;">❌ Error: ${err.message}</p>`;
    }
  }
}

// Simulasi data absensi (dalam implementasi nyata, ini akan dari database)
const attendanceData = {
  'algoritma': {
    nama: 'Algoritma dan Struktur Data',
    jadwal: 'Senin, 08:00-10:00',
    dosen: 'Dr. Budi Santoso',
    pertemuan: 14,
    kehadiran: [
      { tanggal: '2025-01-15', status: 'hadir' },
      { tanggal: '2025-01-08', status: 'hadir' },
      { tanggal: '2025-01-01', status: 'alpha' },
      { tanggal: '2024-12-25', status: 'hadir' },
      { tanggal: '2024-12-18', status: 'sakit' },
    ]
  },
  'pemrograman': {
    nama: 'Pemrograman Web',
    jadwal: 'Selasa, 10:00-12:00',
    dosen: 'Prof. Siti Aminah',
    pertemuan: 14,
    kehadiran: [
      { tanggal: '2025-01-14', status: 'hadir' },
      { tanggal: '2025-01-07', status: 'hadir' },
      { tanggal: '2024-12-31', status: 'hadir' },
      { tanggal: '2024-12-24', status: 'izin' },
      { tanggal: '2024-12-17', status: 'hadir' },
    ]
  },
  'database': {
    nama: 'Basis Data',
    jadwal: 'Rabu, 13:00-15:00',
    dosen: 'Dr. Ahmad Rahman',
    pertemuan: 14,
    kehadiran: [
      { tanggal: '2025-01-13', status: 'hadir' },
      { tanggal: '2025-01-06', status: 'alpha' },
      { tanggal: '2024-12-30', status: 'hadir' },
      { tanggal: '2024-12-23', status: 'hadir' },
      { tanggal: '2024-12-16', status: 'hadir' },
    ]
  }
};

function startRFID() {
  const rfid = new RFID();
  rfid.start();
  window.rfidInstance = rfid; // Simpan instance untuk digunakan nanti
}

// Fungsi yang dipanggil setelah face recognition berhasil
function onFaceRecognitionSuccess() {
  document.getElementById('face-section').style.display = 'none';
  document.getElementById('attendance-section').style.display = 'block';
}

// Fungsi untuk submit absensi
function submitAbsensi() {
  const matkulSelect = document.getElementById('matkul-select');
  const selectedMatkul = matkulSelect.value;
  
  if (!selectedMatkul) {
    alert('Silakan pilih mata kuliah terlebih dahulu!');
    return;
  }
  
  const currentDate = new Date().toISOString().split('T')[0];
  const currentTime = new Date().toLocaleTimeString('id-ID');
  
  // Simulasi penyimpanan absensi (dalam implementasi nyata, kirim ke server)
  const attendanceRecord = {
    nama: window.rfidInstance.currentUser.nama,
    nim: window.rfidInstance.currentUser.nim,
    matkul: selectedMatkul,
    matkulNama: attendanceData[selectedMatkul].nama,
    tanggal: currentDate,
    waktu: currentTime,
    status: 'hadir'
  };
  
  // Tambahkan ke data kehadiran
  if (attendanceData[selectedMatkul]) {
    attendanceData[selectedMatkul].kehadiran.unshift({
      tanggal: currentDate,
      status: 'hadir'
    });
  }
  
  // Tampilkan halaman sukses
  showSuccessPage(attendanceRecord);
  
  // Tampilkan status absensi
  showAttendanceStatus(selectedMatkul);
}

// Fungsi untuk menampilkan halaman sukses
function showSuccessPage(record) {
  const successDetails = document.getElementById('success-details');
  successDetails.innerHTML = `
    <p><strong>Nama:</strong> ${record.nama}</p>
    <p><strong>NIM:</strong> ${record.nim}</p>
    <p><strong>Mata Kuliah:</strong> ${record.matkulNama}</p>
    <p><strong>Tanggal:</strong> ${record.tanggal}</p>
    <p><strong>Waktu:</strong> ${record.waktu}</p>
    <p><strong>Status:</strong> <span class="status-hadir">✅ HADIR</span></p>
  `;
  
  document.getElementById('attendance-section').style.display = 'none';
  document.getElementById('success-section').style.display = 'block';
}

// Fungsi untuk menampilkan status absensi
function showAttendanceStatus(matkul) {
  const attendanceHistory = document.getElementById('attendance-history');
  const attendanceDetails = document.getElementById('attendance-details');
  
  const data = attendanceData[matkul];
  if (!data) return;
  
  let hadirCount = 0;
  let alphaCount = 0;
  let izinCount = 0;
  let sakitCount = 0;
  
  let kehadiranHtml = '<h4>📅 Riwayat Kehadiran (5 Terakhir):</h4>';
  
  data.kehadiran.forEach(item => {
    const statusClass = `status-${item.status}`;
    const statusIcon = {
      'hadir': '✅',
      'alpha': '❌',
      'izin': '⚠️',
      'sakit': '🏥'
    };
    
    kehadiranHtml += `
      <div class="attendance-status">
        <strong>${item.tanggal}</strong>: 
        <span class="${statusClass}">${statusIcon[item.status]} ${item.status.toUpperCase()}</span>
      </div>
    `;
    
    // Hitung statistik
    switch(item.status) {
      case 'hadir': hadirCount++; break;
      case 'alpha': alphaCount++; break;
      case 'izin': izinCount++; break;
      case 'sakit': sakitCount++; break;
    }
  });
  
  const totalPertemuan = data.kehadiran.length;
  const persentaseKehadiran = ((hadirCount / totalPertemuan) * 100).toFixed(1);
  
  attendanceDetails.innerHTML = `
    <div class="attendance-status">
      <strong>📚 Mata Kuliah:</strong> ${data.nama}<br>
      <strong>👨‍🏫 Dosen:</strong> ${data.dosen}<br>
      <strong>🕐 Jadwal:</strong> ${data.jadwal}<br>
      <strong>📊 Total Pertemuan:</strong> ${totalPertemuan} dari ${data.pertemuan}
    </div>
    
    <div class="attendance-status">
      <strong>📈 Statistik Kehadiran:</strong><br>
      <span class="status-hadir">✅ Hadir: ${hadirCount}</span><br>
      <span class="status-alpha">❌ Alpha: ${alphaCount}</span><br>
      <span class="status-izin">⚠️ Izin: ${izinCount}</span><br>
      <span class="status-sakit">🏥 Sakit: ${sakitCount}</span><br>
      <strong>Persentase Kehadiran: ${persentaseKehadiran}%</strong>
    </div>
    
    ${kehadiranHtml}
  `;
  
  attendanceHistory.style.display = 'block';
}

// Fungsi untuk reset sistem
function resetSystem() {
  // Reset semua section
  document.getElementById('rfid-section').style.display = 'block';
  document.getElementById('face-section').style.display = 'none';
  document.getElementById('attendance-section').style.display = 'none';
  document.getElementById('success-section').style.display = 'none';
  document.getElementById('attendance-history').style.display = 'none';
  
  // Reset form
  document.getElementById('matkul-select').value = '';
  document.getElementById('lanjut-face-btn').style.display = 'none';
  
  // Reset content
  document.getElementById('app-content').innerHTML = '<p>🔄 Menunggu kartu RFID ditempel...</p>';
  document.getElementById('status').innerHTML = '📷 Klik Lanjut untuk memulai face recognition...';
  
  // Restart RFID
  startRFID();
}

window.addEventListener('DOMContentLoaded', () => {
  startRFID();
});

// Fungsi placeholder untuk face recognition (akan dipanggil dari script_face.js)
function runFaceRecognition() {
  document.getElementById('rfid-section').style.display = 'none';
  document.getElementById('face-section').style.display = 'block';
  
  // Simulasi face recognition berhasil setelah 3 detik (ganti dengan logic asli)
  setTimeout(() => {
    document.getElementById('status').innerHTML = '✅ Face Recognition Berhasil!';
    setTimeout(() => {
      onFaceRecognitionSuccess();
    }, 1000);
  }, 3000);
}
</script>
</body>
</html>