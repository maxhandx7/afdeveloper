<!DOCTYPE html>
<html>

<head>
    <title>Nuevo mensaje recibido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li strong {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="container">
                        <h2>Hola Alan</h2>
                        <p>Tienes un nuevo mensaje de {{ $nombre }},</p>

                        <p>{{ $mensaje }}</p>

                        

                        <p>Información de contacto</p>
                        <span>{{ $telefono }}</span><br>
                        <span>{{ $correo }}</span><br>

                        <p>Atentamente,<br>{{ $business->name }}</p>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>