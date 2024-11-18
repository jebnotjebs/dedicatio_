<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dedication App with Multi-line and Drag (jQuery)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Nosifer&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; }
        canvas { border: 1px solid #ccc; margin-top: 20px; cursor: pointer; }
        .controls { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; justify-content: center; }
        .controls input, .controls select { padding: 5px; }
        textarea { font-family: Arial, sans-serif; }
    </style>
</head>
<body>

    <h2>Add Dedication to Image</h2>

    <!-- Controls for input -->
    <div class="controls">
        <input type="file" id="backgroundImage" accept="image/*" />
        <textarea id="dedicationText" placeholder="Enter your dedication"></textarea>
        <select id="fontStyle">
            <option value="Arial">Arial</option>
            <option value="Georgia">Georgia</option>
            <option value="Courier New">Courier New</option>
            <option value="Times New Roman">Times New Roman</option>
            <option value="Birthstone">Birthstone</option>
        </select>
        <input type="color" id="fontColor" value="#000000" />
        <input type="range" id="fontSize" min="10" max="60" value="30" />
        <button id="saveBtn">Download</button>
    </div>

    <!-- Canvas for image and text rendering -->
    <canvas id="dedicationCanvas" width="500" height="500"></canvas>

    <script>
        $(document).ready(function() {
            const canvas = $('#dedicationCanvas')[0];
            const ctx = canvas.getContext('2d');
            let img = new Image();
            
            // Text properties
            let text = "Enter your dedication";
            let fontSize = 30;
            let fontStyle = "Arial";
            let fontColor = "#000000";
            
            // Position and dragging
            let textX = canvas.width / 2;
            let textY = canvas.height / 2;
            let isDragging = false;

            // Load and draw the selected background image
            $('#backgroundImage').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        img.onload = function() {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            updateCanvas();
                        };
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Update the canvas with text in real-time
            function updateCanvas() {
                text = $('#dedicationText').val() || "Enter your dedication";
                fontStyle = $('#fontStyle').val();
                fontColor = $('#fontColor').val();
                fontSize = $('#fontSize').val();

                // Clear and redraw image and text
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                // Split the text into lines
                const lines = text.split('\n');
                ctx.font = `${fontSize}px ${fontStyle}`;
                ctx.fillStyle = fontColor;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // Draw each line of text
                let lineHeight = fontSize * 1.2; // Adjust line height for spacing
                lines.forEach((line, index) => {
                    let lineY = textY + (index - Math.floor(lines.length / 2)) * lineHeight;
                    ctx.fillText(line, textX, lineY);
                });
            }

            // Handle mouse and touch events for dragging
            $('#dedicationCanvas').on('mousedown touchstart', function(e) {
                const pos = getEventPos(e);
                if (isTextUnderMouse(pos)) {
                    isDragging = true;
                }
            });

            $('#dedicationCanvas').on('mousemove touchmove', function(e) {
                if (isDragging) {
                    e.preventDefault(); // Prevent scrolling on mobile
                    const pos = getEventPos(e);
                    textX = pos.x;
                    textY = pos.y;
                    updateCanvas();
                }
            });

            $('#dedicationCanvas').on('mouseup touchend', function() {
                isDragging = false;
            });

            // Get position from mouse or touch event
            function getEventPos(e) {
                const rect = canvas.getBoundingClientRect();
                if (e.touches) {
                    return {
                        x: e.touches[0].clientX - rect.left,
                        y: e.touches[0].clientY - rect.top
                    };
                } else {
                    return {
                        x: e.clientX - rect.left,
                        y: e.clientY - rect.top
                    };
                }
            }

            // Check if the mouse or touch is over the text
            function isTextUnderMouse(pos) {
                const metrics = ctx.measureText(text);
                const textWidth = metrics.width;
                const textHeight = fontSize; // Approximate height with font size
                return pos.x >= textX - textWidth / 2 &&
                    pos.x <= textX + textWidth / 2 &&
                    pos.y >= textY - textHeight / 2 &&
                    pos.y <= textY + textHeight / 2;
            }

            // Save the canvas image with text
            $('#saveBtn').click(function() {
                const link = document.createElement('a');
                link.href = canvas.toDataURL();
                link.download = 'dedication.png';
                link.click();
            });

            // Update canvas on user inputs
            $('#dedicationText, #fontStyle, #fontColor, #fontSize').on('input change', function() {
                updateCanvas();
            });
        });
    </script>
</body>
</html>
