const video = document.getElementById('video')
const statusDiv = document.getElementById('status')

// Load face-api.js models
Promise.all([
  faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('/models')
]).then(startVideo)

function startVideo() {
  navigator.mediaDevices.getUserMedia({ video: {} })
    .then(stream => video.srcObject = stream)
    .catch(err => console.error('Camera error:', err))
}

// Dummy labeled face descriptors (1 orang, bisa ditambah)
async function loadLabeledImages() {
  const labels = ['Apri'] // ganti sesuai nama orang
  return Promise.all(
    labels.map(async label => {
      const img = await faceapi.fetchImage(`/known_faces/${label}.jpg`)
      const detections = await faceapi
        .detectSingleFace(img)
        .withFaceLandmarks()
        .withFaceDescriptor()
      return new faceapi.LabeledFaceDescriptors(label, [detections.descriptor])
    })
  )
}

video.addEventListener('play', async () => {
  const labeledDescriptors = await loadLabeledImages()
  const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.6)

  const canvas = faceapi.createCanvasFromMedia(video)
  document.body.append(canvas)
  const displaySize = { width: video.width, height: video.height }
  faceapi.matchDimensions(canvas, displaySize)

  setInterval(async () => {
    const detections = await faceapi
      .detectAllFaces(video)
      .withFaceLandmarks()
      .withFaceDescriptors()
    const resizedDetections = faceapi.resizeResults(detections, displaySize)
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)

    const results = resizedDetections.map(d =>
      faceMatcher.findBestMatch(d.descriptor)
    )

    results.forEach((result, i) => {
      const box = resizedDetections[i].detection.box
      const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
      drawBox.draw(canvas)

      if (result.label !== 'unknown') {
        statusDiv.innerText = `✅ Berhasil Absen: ${result.label}`
        // Kirim ke backend PHP
        fetch('submit_absen.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ nama: result.label })
        })
      } else {
        statusDiv.innerText = '❌ Wajah tidak dikenali'
      }
    })
  }, 1000)
})
