<!DOCTYPE html>
<html>
    <head>
        <title>{{ _("Weather") }}</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style>
            .sensor-container > div {
                border: 1px solid black;
                padding: 8px;
                margin: 4px;
            }

            .sensor-container {
                margin-bottom: 16px;
                margin-top: 16px;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                font-family: 'Lato';
                font-size: 32px;
                font-weight: bold;
            }

            .title {
                font-size: 24px;
            }

            .refresh {
                margin-top: 16px;
            }
        </style>
    </head>
    <body>

        @section('container')
            <div class="container-fluid">
                @yield('content')
            </div>
        @show
    </body>
</html>
