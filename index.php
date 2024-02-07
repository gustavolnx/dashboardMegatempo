<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .red-text {
            color: red;
        }

        .green-text {
            color: green;
        }

        .scene {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .title {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="container mt-3">
        <table class="table table-hover table-striped">
            <tr class="row w-100">
                <!-- NOME -->
                <th class="col">Nome da Playlist</th>
                <!-- Publicidades -->
                <th class="col">Publicidades</th>
                <!-- Comunicados -->
                <th class="col">Comunicados</th>
                <!-- Notícias -->
                <th class="col">Notícias</th>
                <!-- Total -->
                <th class="col">Total</th>
            </tr>
            <?php
            $playlistIds = [780, 767];
            date_default_timezone_set('America/Sao_Paulo');
            $today = date('Y-m-d H:i:s');

            foreach ($playlistIds as $index => $playlistId) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.4yousee.com.br/v1/playlists/' . $playlistId,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Secret-Token: 809ea74915c0fdc86f9844f8960a65bf'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $data = json_decode($response, true);
                $playlistName = $data["name"];
                $item = $data["items"];
                // ? Playlist
                $countComunicados = 0;
                $countPublicidades = 0;
                $countNoticias = 0;
                $totalItens = 0;
                foreach ($item  as $item2) {
                    // ? Carousel
                    if ($item2["type"] == "carousel") {
                        // ? Itens
                        foreach ($item2["items"] as $item3) {
                            if (isset($item3["contentSchedule"]["endDate"])) {

                                if ($item3["contentSchedule"]["endDate"] < $today) {
                                    echo "<script>console.log('Vencido [" . $item3["id"] . "]')</script>";
                                    continue;
                                }
                            }

                            if (isset($item3["playlistSchedule"]["endDate"])) {

                                if ($item3["playlistSchedule"]["endDate"] < $today) {
                                    echo "<script>console.log('Vencido [" . $item3["id"] . "]')</script>";
                                    continue;
                                }
                            }

                            if (isset($item3["contentSchedule"]["startDate"])) {

                                if ($item3["contentSchedule"]["startDate"] > $today) {
                                    echo "<script>console.log('Ainda não começou [" . $item3["id"] . "]')</script>";
                                    continue;
                                }
                            }
                            if (isset($item3["playlistSchedule"]["startDate"])) {

                                if ($item3["playlistSchedule"]["startDate"] > $today) {
                                    echo "<script>console.log('Ainda não começou [" . $item3["id"] . "]')</script>";
                                    continue;
                                }
                            }
                            if (strpos($item2["name"], 'Comuni') !== false) {
                                $countComunicados++;
                            }
                            if (strpos($item2["name"], 'Publi') !== false) {
                                $countPublicidades++;
                            }
                            if (strpos($item2["name"], 'Notícias') !== false) {
                                $countNoticias++;
                            }
                            $totalItens++;
                        }
                        echo "<script>console.log('Total de Itens: " . $totalItens . "')</script>";
                    } else {
                        $countNoticias++;
                        $totalItens++;
                    }
                }
            ?>
                <tr class="row w-100">
                    <th class="col"><?php echo $playlistName; ?></th>
                    <th class="col"><?php echo $countPublicidades; ?></th>
                    <th class="col"><?php echo $countComunicados; ?></th>
                    <th class="col"><?php echo $countNoticias; ?></th>
                    <th class="col <?= $totalItens > 30 ? 'red-text' : 'green-texts'; ?>"><?php echo $totalItens; ?></th>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>