<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saints PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff;
            color: #343a40;
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .saint-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
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
    </style>
</head>
<body>
    <h1>List of Saints</h1>
    <ul class="saint-list">
        @foreach($saints as $saint)
            <li class="saint-item">
                <strong>{{ $saint->name }}</strong>
                <br>
                {{ $saint->description }}
                <br>
                <strong>Feast Day: </strong>{{ $saint->feast_day }}
                <br>
                <hr>
            </li>
        @endforeach
    </ul>
</body>
</html>
