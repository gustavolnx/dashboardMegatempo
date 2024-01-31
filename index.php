<?php

$playlistId = isset($_GET['playlistId']) ? $_GET['playlistId'] : null;
$totalSize = 0;

if ($playlistId === null) {
    echo "ID não informado na URL.";
} else {
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

    if (isset($data["items"]) && is_array($data["items"])) {
        // Playlist pai

        foreach ($data["items"] as $item) {
            if (isset($item["type"]) && $item["type"] === "carousel") {
                // Exibe o "name" e o tamanho do campo "sequence" para cada item


                // Verifica se o campo "sequence" existe e é um array
                if (isset($item["sequence"]) && is_array($item["sequence"])) {
                    // Verifica se a palavra "Comunicado" ou "Publicidade" está presente no nome
                    if (stripos($item["name"], "Comunicado") !== false || stripos($item["name"], "Publicidade") !== false) {
                        $itemSize = sizeof($item["sequence"]) * 2;
                    } else {
                        $itemSize = sizeof($item["sequence"]);
                    }


                    $totalSize += $itemSize;
                } else {
                    // Se não for um array, o tamanho é 1

                    $totalSize += 1;
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <style>
        .red-text {
            color: red;
        }

        .green-text {
            color: green;
        }
    </style>
</head>

<body>
    <div class="scene-1">

        <DIV class="title">
            <h2 class="playlist">Playlist : <SPAN><?php echo isset($data["name"]) ? $data["name"] : ''; ?></SPAN> </h2>
            <br>
            <h2 class="id">ID : <span><?php echo $playlistId; ?></span> </h2>
        </DIV>

        <table class="table table-bordered" id="tableInfos">
            <THEAD class="">
                <TR>
                    <Th scope="col">Conteudo</Th>
                    <Th scope="col">Itens</Th>
                </TR>
            </THEAD>
            <tbody>
                <?php
                // Initialize $totalSize outside the loop
                $totalSize = 0;

                foreach ($data["items"] as $item) :
                    if (isset($item["type"]) && $item["type"] === "carousel") :
                ?>
                        <tr>
                            <td scope="row" id="nameContent"><?php echo $item["name"]; ?></td>
                            <?php
                            if (isset($item["sequence"]) && is_array($item["sequence"])) {
                                if (stripos($item["name"], "Comunicado") !== false || stripos($item["name"], "Publicidade") !== false) {
                                    $itemSize = sizeof($item["sequence"]) * 2;
                                } else {
                                    $itemSize = sizeof($item["sequence"]);
                                }
                            } else {
                                $itemSize = 1;
                            }
                            ?>
                            <td scope="row" id="totalItens"><?php echo $itemSize; ?></td>
                        </tr>
                <?php
                        // Increment $totalSize inside the loop for each item
                        $totalSize += $itemSize;
                    endif;
                endforeach;
                ?>
            </tbody>

            <div class="totalAllItensBox">
                <h2 class="totalAllItens <?php echo ($totalSize > 18) ? 'red-text' : 'green-text'; ?>">Total de Itens : <span><?php echo $totalSize; ?></span></h2>
            </div>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
</body>

</html>