<!DOCTYPE html>
<html>
<head>
<title>Capture Pet Image</title>
<style>
video, canvas {
    width: 300px;
    border: 2px solid black;
}
</style>
</head>
<body>

<h2>Take a Pet Photo</h2>

<video id="video" autoplay></video>
<br>
<button onclick="capture()">Capture Image</button>

<form id="uploadForm" action="match_api.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="captured_image" id="captured_image">
    <button type="submit" style="margin-top:20px;">Match Pet</button>
</form>

<canvas id="canvas" style="display:none;"></canvas>

<script>
// Start webcam
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => video.srcObject = stream);

// Capture frame
function capture() {
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.drawImage(video, 0, 0);
    
    // Convert to base64
    const data = canvas.toDataURL("image/png");
    document.getElementById("captured_image").value = data;

    alert("Image captured! Now click Match Pet.");
}
</script>

</body>
</html>
