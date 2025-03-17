<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        header {
            /* background-color: #f2f2f2; */
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        header img {
            width: 50px;
            height: auto;
        }

        header h1 {
            font-size: 20px;
        }

        table {
            width: 100%;
        }
        thead {
            background-color: #43019b;
        }
        th {
            padding: 10px;
            color: white;
        }
        td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{'data:image/png;base64,' . base64_encode(file_get_contents(@$image)) }}" alt="logo">
        <h1 >Ajyal Al - Madina</h1>
    </header>
    <main>
        <table>
            <thead>
                <th>Header1</th>
                <th>Header2</th>
                <th>Header3</th>
            </thead>
            <tbody>
                <tr>
                    <td>Isi1</td>
                    <td>Isi2</td>
                    <td>Isi3</td>
                </tr>
                <tr>
                    <td>Isi1</td>
                    <td>Isi2</td>
                    <td>Isi3</td>
                </tr>
            </tbody>
        </table>
    </main>
    <!-- <p>Data : {{ $data->id }}</p>
    <p>Username : {{ $data->user_id }}</p>
    <p>User Id : {{ $user->first_name . ' ' . $user->last_name }}</p>
    <p>Type : {{ $data->type }}</p>
    <p>Interval : {{ $data->interval }}</p> -->
</body>
</html>