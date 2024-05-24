<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saints</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
            display: flex;
        }
        .sidebar {
            width: 150px;
            height: 100vh;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
        }
        .sidebar h2 {
            color: #fff;
            text-align: center;
            font-size: 18px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 0;
            margin: 10px 0;
            background-color: #495057;
            text-align: center;
            border-radius: 5px;
            font-size: 14px;
        }
        .sidebar a:hover {
            background-color: #6c757d;
        }
        .container {
            margin-left: 170px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-radius: 8px;
            width: calc(100% - 190px);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
        .saint-list {
            list-style-type: none;
            padding: 0;
        }
        .saint-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .saint-item:last-child {
            border-bottom: none;
        }
        .saint-item strong {
            color: #007bff;
        }
        .create-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .create-link:hover {
            background-color: #0056b3;
        }
        .saint-item hr {
            border: none;
            border-top: 1px solid #dee2e6;
            margin: 10px 0;
        }
        .qr-code-container {
            text-align: center;
        }

        .qr-code-image {
            margin: 0 auto;
        }
        .sidebar a[href="{{ route('saints.pdf') }}"] {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <!-- Replace "Button 1" with a link to the qrcodescanner.blade.php page -->
        <a href="{{ route('qrcodescanner') }}">QR Code Scanner</a>
        <a href="{{ route('saints.pdf') }}">Generate PDF</a>
        <a href="{{ route('csv.view') }}">Generate CSV</a>
        {{-- <a href="#">Button 4</a>
        <a href="#">Button 5</a> --}}
    </div>
    
    <div class="container">
        <h1>List of Saints</h1>
        @if (session('success'))
            <div class="message">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('saints.create') }}" class="create-link">Create New Saint</a>
        <ul class="saint-list">
            @foreach($saints as $saint)
                <li class="saint-item" id="saint-item-{{ $saint->id }}" data-id="{{ $saint->id }}">
                    <strong class="editable">{{ $saint->name }}</strong>
                    <input type="text" class="edit-input" style="display:none" value="{{ $saint->name }}">
                    <br>
                    <strong class="editable">{{ $saint->description }}</strong>
                    <textarea class="edit-input" style="display:none">{{ $saint->description }}</textarea>
                    <br>
                    <strong class="editable">{{ $saint->feast_day }}</strong>
                    <input type="date" class="edit-input" style="display:none" value="{{ $saint->feast_day }}">
                    <br>
                    <button class="edit-button" data-id="{{ $saint->id }}">Edit</button>
                    <button class="save-button" data-id="{{ $saint->id }}" style="display:none">Save</button>
                    <button class="cancel-button" data-id="{{ $saint->id }}" style="display:none">Cancel</button>
                    <button class="delete-button" data-id="{{ $saint->id }}">Delete</button>
                    <button class="qrcode-button" data-id="{{ $saint->id }}">Generate QRCode</button>
                    <hr>
                </li>
            @endforeach
        </ul>
    </div>
    <script>
        const editButtons = document.querySelectorAll('.edit-button');
        const saveButtons = document.querySelectorAll('.save-button');
        const cancelButtons = document.querySelectorAll('.cancel-button');
        const deleteButtons = document.querySelectorAll('.delete-button');
        const qrCodeButtons = document.querySelectorAll('.qrcode-button');

        editButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const saintItem = button.closest('.saint-item');
                const editableFields = saintItem.querySelectorAll('.editable');
                const editInputs = saintItem.querySelectorAll('.edit-input');

                editableFields.forEach(field => field.style.display = 'none');
                editInputs.forEach(input => input.style.display = 'inline-block');

                button.style.display = 'none';
                saveButtons[index].style.display = 'inline-block';
                cancelButtons[index].style.display = 'inline-block';
            });
        });

        saveButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const saintItem = button.closest('.saint-item');
                const saintId = saintItem.getAttribute('data-id');
                const editableFields = saintItem.querySelectorAll('.editable');
                const editInputs = saintItem.querySelectorAll('.edit-input');

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'POST');
                formData.append('name', editInputs[0].value);
                formData.append('description', editInputs[1].value);
                formData.append('feast_day', editInputs[2].value);

                fetch(`/saints/${saintId}`, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                  .then(data => {
                      if (data.message) {
                          editableFields[0].textContent = editInputs[0].value;
                          editableFields[1].textContent = editInputs[1].value;
                          editableFields[2].textContent = editInputs[2].value;
                          editableFields.forEach(field => field.style.display = 'inline-block');
                          editInputs.forEach(input => input.style.display = 'none');
                          editButtons[index].style.display = 'inline-block';
                          saveButtons[index].style.display = 'none';
                          cancelButtons[index].style.display = 'none';
                      }
                  }).catch(error => console.error('Error:', error));
            });
        });

        cancelButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const saintItem = button.closest('.saint-item');
                const editableFields = saintItem.querySelectorAll('.editable');
                const editInputs = saintItem.querySelectorAll('.edit-input');

                editableFields.forEach(field => field.style.display = 'inline-block');
                editInputs.forEach(input => input.style.display = 'none');

                editButtons[index].style.display = 'inline-block';
                saveButtons[index].style.display = 'none';
                cancelButtons[index].style.display = 'none';
            });
        });

        deleteButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const saintItem = button.closest('.saint-item');
                const saintId = saintItem.getAttribute('data-id');

                fetch(`/saints/${saintId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.message) {
                          saintItem.remove();
                      }
                  }).catch(error => console.error('Error:', error));
            });
        });

        qrCodeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const saintId = button.getAttribute('data-id');
                window.open(`/saints/${saintId}/qrcode`, '_blank');
            });
        });
    </script>
</body>
</html>
