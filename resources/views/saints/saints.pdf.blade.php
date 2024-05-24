<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Saints PDF</title>
    <style>
        /* Define your PDF styles here */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>List of Saints</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Feast Day</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saints as $saint)
            <tr>
                <td>{{ $saint->name }}</td>
                <td>{{ $saint->description }}</td>
                <td>{{ $saint->feast_day }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
