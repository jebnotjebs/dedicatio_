$(document).ready(async function () {
    Birthday();
  
});

$('.connectedSortable').sortable({
    placeholder: 'sort-highlight',
    connectWith: '.connectedSortable',
    handle: '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex: 999999
})
$('.connectedSortable .card-header').css('cursor', 'move')



function Birthday() {
	return new Promise((resolve, reject) => {
		$.ajax({
			url: "assets/php/index.php",
			method: "POST",
			data: {
				formula: "birthday_"
			},
			dataType: "json",
			beforeSend: () => {
				$('.overlay').removeClass('d-none');
			},
			success: function (res) {
				$('.overlay').addClass('d-none');
				select_d = res;
				var cardItem  = "";
				if (select_d.length === 0) {
					cardItem  = ` <div class="col-12">
									<h5 class="text-center">No image available</h5>
								  </div>`;
				} else {
					select_d.forEach((x) => {
						cardItem  += ` <div class="col-6 col-md-4 col-lg-3 mb-3">
											<img src="https://app.mrsg.ph/application/system/cake_dedication/_/img/background/${x.image}" data-img_code = ${x.code} class="img-fluid img_background" id="${x.image}">
										</div>`;
					});
				}
				$('#birthday_card').append(cardItem);
				resolve();
			},
			error: function (err) {
                reject(err);
            }
		})
    });
}

$(document).on('click', '.img_background', function(e) {
    e.preventDefault();

    $('#modal_type').modal('show');

    // Set up canvas and context
    const canvas = $('#dedicationCanvas')[0];
    const ctx = canvas.getContext('2d');
    const img = new Image();
    // img.crossOrigin = 'anonymous'; // Set crossOrigin first pag nilagay ko ayaw mag pakita ng image kasi hindi supported ng cors ang server dapat i setup pa
	img.src = `https://app.mrsg.ph/application/system/cake_dedication/_/img/background/${e.target.id}`;

    // Default text properties
    let text = "Enter your dedication";
    let fontSize = 30;
    let fontStyle = "Arial";
    let fontColor = "#000000";

    // Initial text position and drag state
    let textX = canvas.width / 2;
    let textY = canvas.height / 2;
    let isDragging = false;

    // Load and draw the selected background image

    img.onload = function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        updateCanvas();
    };

    img.onerror = function() {
        console.error("Failed to load image due to CORS issues or an invalid URL.");
    };

    // Update the canvas with text
    function updateCanvas() {
        text = $('#dedicationText').val() || "Enter your dedication";
        fontStyle = $('#fontStyle').val();
        fontColor = $('#fontColor').val();
        fontSize = parseInt($('#fontSize').val(), 10) || 30;

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        const lines = text.split('\n');
        ctx.font = `${fontSize}px ${fontStyle}`;
        ctx.fillStyle = fontColor;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        const lineHeight = fontSize * 1.2;
        lines.forEach((line, index) => {
            const lineY = textY + (index - Math.floor(lines.length / 2)) * lineHeight;
            ctx.fillText(line, textX, lineY);
        });
    }

    // Handle dragging for text position
    $('#dedicationCanvas')
        .on('mousedown touchstart', function(e) {
            const pos = getEventPos(e);
            if (isTextUnderMouse(pos)) {
                isDragging = true;
            }
        })
        .on('mousemove touchmove', function(e) {
            if (isDragging) {
                e.preventDefault();
                const pos = getEventPos(e);
                textX = pos.x;
                textY = pos.y;
                updateCanvas();
            }
        })
        .on('mouseup touchend', function() {
            isDragging = false;
        });

    // Get event position for mouse or touch events
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

    // Check if the text is under the mouse/touch position
    function isTextUnderMouse(pos) {
        const metrics = ctx.measureText(text);
        const textWidth = metrics.width;
        const textHeight = fontSize;
        return pos.x >= textX - textWidth / 2 &&
            pos.x <= textX + textWidth / 2 &&
            pos.y >= textY - textHeight / 2 &&
            pos.y <= textY + textHeight / 2;
    }

    // Download the canvas content as an image
    $('#saveBtn').click(function() {
        // Create a new window for printing the canvas
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Canvas</title></head><body>');
        printWindow.document.write('<h2>Printed Canvas</h2>');
        
        // Create a new canvas in the print window and copy the current canvas content
        const printCanvas = printWindow.document.createElement('canvas');
        printCanvas.width = $('#dedicationCanvas')[0].width;
        printCanvas.height = $('#dedicationCanvas')[0].height;
        const printCtx = printCanvas.getContext('2d');
        
        // Copy the content of the original canvas to the print canvas
        printCtx.drawImage($('#dedicationCanvas')[0], 0, 0);
        
        // Append the canvas to the print window's body
        printWindow.document.body.appendChild(printCanvas);
        
        // Wait for the print window to load, then trigger the print dialog
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        // Trigger print dialog
        printWindow.print();
    });

    // Real-time canvas update on user input changes
    $('#dedicationText, #fontStyle, #fontColor, #fontSize').on('input change', updateCanvas);
});




var select_d = [];
