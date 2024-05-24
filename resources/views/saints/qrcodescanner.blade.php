<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <!-- Include jsQR library -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }
        #file-input {
            margin-bottom: 20px;
        }
        #image-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        #output {
            margin-top: 20px;
            font-size: 18px;
            color: #28a745;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>QR Code Scanner</h1>
    <input type="file" accept="image/*" id="file-input">
    <div id="image-container"></div>
    <div id="output"></div>
    <a href="{{ route('saints.index') }}" class="back-button">Back to List of Saints</a>

    <script>
        const fileInput = document.getElementById('file-input');
        const imageContainer = document.getElementById('image-container');
        const outputContainer = document.getElementById('output');

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    imageContainer.innerHTML = '';
                    imageContainer.appendChild(img);

                    // Create a canvas to draw the image for QR code scanning
                    const canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                    // Get the image data from the canvas
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                    // Scan the QR code
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code) {
                        outputContainer.innerText = 'QR Code content: ' + code.data;
                    } else {
                        outputContainer.innerText = 'No QR code found in the image.';
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>
</html>
