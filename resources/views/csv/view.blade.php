<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        input[type="file"] {
            display: none;
        }
        label {
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
        }
        label:hover {
            background-color: #0056b3;
        }
        #csvDataContainer {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e2e6ea;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .back-button a {
            text-decoration: none;
            color: #007bff;
            padding: 10px 20px;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button a:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage CSV Files</h1>
        <button onclick="exportCSV()">Export CSV</button>
        <input type="file" id="csvFileInput" accept=".csv" onchange="importCSV()">
        <label for="csvFileInput">Browse CSV</label>

        <div id="csvDataContainer"></div>

        <div class="back-button">
            <a href="{{ route('saints.index') }}">Back</a>
        </div>
    </div>

    <script>
        function exportCSV() {
            // Retrieve data from the server
            fetch('/saints/export')
                .then(response => response.blob())
                .then(blob => {
                    // Create a blob URL for the CSV file
                    const url = window.URL.createObjectURL(new Blob([blob]));
                    // Create a link element
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'saints.csv'; // Set the file name
                    document.body.appendChild(a);
                    a.click(); // Trigger the download
                    window.URL.revokeObjectURL(url); // Release the object URL
                })
                .catch(error => console.error('Error:', error));
        }

        function importCSV() {
            const fileInput = document.getElementById('csvFileInput');
            const file = fileInput.files[0];
            if (!file) {
                console.error('No file selected');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const csvData = event.target.result;
                displayCSVData(csvData);
            };
            reader.readAsText(file);
        }

        function displayCSVData(csvData) {
            const csvDataContainer = document.getElementById('csvDataContainer');
            csvDataContainer.innerHTML = ''; // Clear previous data
            
            const rows = csvData.split('\n');
            const table = document.createElement('table');
            const headerRow = document.createElement('tr');
            rows[0].split(',').forEach(column => {
                const th = document.createElement('th');
                th.textContent = column.trim();
                headerRow.appendChild(th);
            });
            table.appendChild(headerRow);
            for (let i = 1; i < rows.length; i++) {
                const columns = rows[i].split(',');
                const tr = document.createElement('tr');
                columns.forEach(column => {
                    const td = document.createElement('td');
                    td.textContent = column.trim();
                    tr.appendChild(td);
                });
                table.appendChild(tr);
            }
            csvDataContainer.appendChild(table);
        }
    </script>
</body>
</html>
