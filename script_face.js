const video = document.getElementById('video');
const statusDiv = document.getElementById('status');

let faceMatcher = null;
let labeledDescriptors = null;
let alreadyVerified = false;
let canvas = null;

async function loadModels() {
  console.log("📦 Memuat model...");
  statusDiv.innerText = '📦 Memuat model...';

  await Promise.all([
    faceapi.nets.faceRecognitionNet.loadFromUri('/RECOGNITION/models/face_recognition'),
    faceapi.nets.faceLandmark68Net.loadFromUri('/RECOGNITION/models/face_landmark_68'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('/RECOGNITION/models/ssd_mobilenetv1')
  ]);

  console.log("✅ Model sudah dimuat.");
  statusDiv.innerText = '✅ Model dimuat. Memulai kamera...';
}


function startVideo() {
  console.log("🎥 Memulai kamera...");
  return navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
      statusDiv.innerText = '🎥 Kamera aktif. Menunggu wajah...';
      console.log("✅ Kamera aktif.");
    })
    .catch(err => {
      console.error('❌ Gagal akses kamera:', err);
      statusDiv.innerText = '❌ Gagal akses kamera: ' + err.message;
      throw err;
    });
}

async function loadLabeledImages() {
  const response = await fetch('get_known_faces.php');
  const labels = await response.json();
  console.log("📄 Label ditemukan dari server:", labels);

  const result = await Promise.all(
    labels.map(async label => {
      const imgUrl = `/RFID/known_faces/${label}.jpg`;
      console.log(`🖼️ Memuat gambar: ${imgUrl}`);

      const img = await faceapi.fetchImage(imgUrl);
      const detection = await faceapi
        .detectSingleFace(img)
        .withFaceLandmarks()
        .withFaceDescriptor();

      if (!detection) {
        console.error(`❌ Wajah TIDAK terdeteksi di ${label}.jpg`);
        return null;
      }

      console.log(`✅ Wajah TERDETEKSI & dimuat untuk: ${label}`);
      return new faceapi.LabeledFaceDescriptors(label, [detection.descriptor]);
    })
  );

  const valid = result.filter(e => e !== null);
  console.log("📝 Labeled descriptors yang valid:", valid.map(v => v.label));
  return valid;
}

async function runFaceRecognition() {
  document.getElementById('face-section').style.display = 'block';
  document.getElementById('lanjut-face-btn').style.display = 'none';

  try {
    await loadModels();
    await startVideo();

    labeledDescriptors = await loadLabeledImages();

    if (!labeledDescriptors || labeledDescriptors.length === 0) {
      console.error("🚨 Tidak ada wajah yang dikenali.");
      statusDiv.innerText = '🚨 Tidak ada wajah yang dikenali.';
      return;
    }

    faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.8);
    console.log("✅ FaceMatcher siap dengan threshold 0.8");

    if (!canvas) {
      canvas = faceapi.createCanvasFromMedia(video);
      canvas.id = 'faceCanvas';
      canvas.style.position = 'absolute';
      canvas.style.top = '0';
      canvas.style.left = '0';
      canvas.style.pointerEvents = 'none';
      canvas.style.zIndex = '10';
      document.querySelector('.video-container').appendChild(canvas);
    }

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      console.log("🔍 Mendeteksi frame…");

      const detections = await faceapi
        .detectAllFaces(video)
        .withFaceLandmarks()
        .withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

      const results = resizedDetections.map(d =>
        faceMatcher.findBestMatch(d.descriptor)
      );

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
        drawBox.draw(canvas);

        console.log(`🔍 Best match: ${result.label}, Distance: ${result.distance?.toFixed(2)}`);

        if (result.label !== 'unknown') {
          statusDiv.innerText = `✅ Berhasil Absen: ${result.label}`;

          if (!alreadyVerified) {
            alreadyVerified = true;

            fetch('submit_absen.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ nama: result.label })
            })
              .then(() => console.log(`📤 Data absen terkirim untuk ${result.label}`))
              .catch(err => console.error('❌ Gagal kirim data absen:', err));

            setTimeout(() => {
              alreadyVerified = false;
            }, 5000);
          }
        } else {
          statusDiv.innerText = '❌ Wajah tidak dikenali';
        }
      });
    }, 1000);

  } catch (err) {
    console.error("🚨 Terjadi kesalahan:", err);
    statusDiv.innerText = '🚨 Terjadi kesalahan, cek console.';
  }
}

window.runFaceRecognition = runFaceRecognition;
