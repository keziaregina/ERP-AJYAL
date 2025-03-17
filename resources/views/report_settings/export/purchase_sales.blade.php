<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase & Sales Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 15px;
        }

        .container {}

        .card {
            background-color: #f4f7fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-width: 300px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #444;
            font-size: 18px;
        }

        .card table {
            width: 100%;
            border-collapse: collapse;
        }

        .card table td {
            padding: 6px 0;
            color: #333;
        }

        .label {
            font-weight: bold;
        }

        .info {
            color: #3498db;
            cursor: pointer;
            margin-left: 5px;
        }

        .overall {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .overall h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #444;
        }

        .negative {
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(@$image)) }}" alt="logo">
        <h1>Ajyal Al - Madina</h1>
    </header>
    <main>
        <div class="container">
            <div class="card">
                <h3>Purchases</h3>
                <table>
                    <tr>
                        <td class="label">Total Purchase:</td>
                        <td>4,830.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Including tax:</td>
                        <td>5,440.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Total Purchase Return Including Tax:</td>
                        <td>0.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Due: </td>
                        <td>5,135.000 ៛</td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <h3>Sales</h3>
                <table>
                    <tr>
                        <td class="label">Total Sale:</td>
                        <td>0.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Sale Including tax:</td>
                        <td>0.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Total Sell Return Including Tax:</td>
                        <td>0.000 ៛</td>
                    </tr>
                    <tr>
                        <td class="label">Sale Due:</td>
                        <td>0.000 ៛</td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

    <div class="overall">
        <h3>Overall</h3>
        <h4>(Sale - Sell Return) - (Purchase - Purchase Return)</h4>
        <table>
            <tr class="negative">
                <td>Sale - Purchase</td>
                <td>:</td>
                <td>-5,440.000</td>
            </tr>
            <tr class="negative">
                <td>Due amount</td>
                <td>:</td>
                <td>-5,440.000</td>
            </tr>
        </table>
    </div>

</body>

</html>