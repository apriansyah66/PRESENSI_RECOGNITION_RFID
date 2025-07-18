<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Absen Wajah</title>
<script defer src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
</head>
<body>

<h2>Absen Wajah Mahasiswa</h2>
<video id="video" width="720" height="560" autoplay muted></video>
<div id="status"></div>

<script>
const video = document.getElementById('video');

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/models')
]).then(startVideo);

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: {} })
    .then(stream => video.srcObject = stream)
    .catch(err => console.error(err));
}

video.addEventListener('play', async () => {
  const labeledDescriptors = await loadLabeledImages();
  const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6);

  const canvas = faceapi.createCanvasFromMedia(video);
  document.body.append(canvas);
  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  setInterval(async () => {
    const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks().withFaceDescriptors();
    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);

    const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

    results.forEach((result, i) => {
      const box = resizedDetections[i].detection.box;
      const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() });
      drawBox.draw(canvas);

      if (!result.label.includes('unknown')) {
        document.getElementById('status').innerText = `✅ Terdeteksi: ${result.label}`;
        sendAbsen(result.label);
      }
    });
  }, 1000);
});

async function loadLabeledImages() {
  const labels = [<?php
    // generate UID list from PHP
    $files = array_diff(scandir('Recognition/known_faces'), ['.', '..']);
    $uids = array_map(fn($f) => pathinfo($f, PATHINFO_FILENAME), $files);
    echo '"' . implode('","', $uids) . '"';
  ?>];
  return Promise.all(
    labels.map(async label => {
      const imgUrl = `/Recognition/known_faces/${label}.jpg`;
      const img = await faceapi.fetchImage(imgUrl);
      const detections = await faceapi.detectSingleFace(img)
        .withFaceLandmarks().withFaceDescriptor();
      return new faceapi.LabeledFaceDescriptors(label, [detections.descriptor]);
    })
  );
}

function sendAbsen(uid) {
  fetch('absen_face.php', {
    method: 'POST',
    body: new URLSearchParams({ uid })
  }).then(res => res.text()).then(msg => console.log(msg));
}
</script>
</body>
</html>
