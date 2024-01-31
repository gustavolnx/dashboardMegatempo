<?php


//  código exibindo o conteúdo das playlists
$playlistId = isset($_GET['playlistId']) ? $_GET['playlistId'] : null;

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
        foreach ($data["items"] as $item) {
            echo "Type: " . $item["type"] . "<br>";
            echo "ID : " . $item["id"] . "<br>";
            echo "Name: " . $item["name"] . "<br>";

            if (isset($item["items"]) && is_array($item["items"])) {
                foreach ($item["items"] as $itemCutted) {
                    echo "Cutted Type : " . $itemCutted["type"] . "<br>";
                    echo "Cutted ID : " . $itemCutted["id"] . "<br>";
                    echo "Cutted Name : " . $itemCutted["name"] . "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                }
            }
        }
    } else {
        echo "Playlist de ID $playlistId não existe ou encontra-se vazia. Gentileza inserir conteúdos para salvá-la.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>