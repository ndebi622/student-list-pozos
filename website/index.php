<html>
<head>
    <title>POZOS</title>
</head>

<body>

<h1>Student Checking App</h1>

<form action="" method="POST">
    <button type="submit" name="submit">
        List Student
    </button>
</form>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit']))
{
    /*
    ============================================
    Lecture des variables d'environnement Docker
    ============================================
    */

    $username = getenv('USERNAME');
    $password = getenv('PASSWORD');
    $api      = getenv('API');
    $port     = getenv('API_PORT');

    /*
    ============================================
    Valeurs par défaut si variables absentes
    ============================================
    */

    if(empty($username)) $username = "fake_username";
    if(empty($password)) $password = "fake_password";

    /*
    IMPORTANT :
    api = nom du service Docker Compose
    port = port interne du container API Flask
    */

    if(empty($api))  $api  = "api";
    if(empty($port)) $port = "5000";

    /*
    ============================================
    Construction du header HTTP Basic Auth
    ============================================
    */

    $context = stream_context_create(array(
        "http" => array(
            "header" => "Authorization: Basic " .
            base64_encode("$username:$password")
        )
    ));

    /*
    ============================================
    URL de l'API Flask
    ============================================
    */

    $url = "http://{$api}:{$port}/pozos/api/v1.0/get_student_ages";

    /*
    ============================================
    Appel API Flask
    ============================================
    */

    $response = file_get_contents($url, false, $context);

    /*
    ============================================
    Conversion JSON → tableau PHP
    ============================================
    */

    $list = json_decode($response, true);

    /*
    ============================================
    Affichage
    ============================================
    */

    echo "<p style='color:red; font-size:20px;'>
            This is the list of the student with age
          </p>";

    foreach($list["student_ages"] as $key => $value)
    {
        echo "- $key are $value years old <br>";
    }
}

?>

</body>
</html>
