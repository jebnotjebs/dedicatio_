<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas Image Test with Download</title>
</head>
<body>
    <canvas id="testCanvas" width="200" height="300"></canvas>
    <button id="downloadBtn">Download Image</button>

    <script>
        const canvas = document.getElementById('testCanvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = 'https://picsum.photos/200/300';

        img.onload = function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            console.log('Image loaded and drawn on canvas.');
        };

        img.onerror = function() {
            console.error('Failed to load image.');
        };

        // Download button functionality
        document.getElementById('downloadBtn').addEventListener('click', function() {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = 'canvas_image.png';
            link.click();
        });
    </script>
</body>
</html>
