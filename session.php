<!DOCTYPE html>
<html>
<head>
    <title>Session</title>
    <style>
        body {
            text-align: center;
        }
        .container {
            margin: 0 auto;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Session</h2>
        <?php
        session_start();

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION["utilisateur_id"])) {
            header("Location: login.php");
            exit;
        }

        // Afficher le nom d'utilisateur ou d'autres informations de session
        // Remplacez cela par les informations que vous souhaitez afficher une fois connecté.
        echo "Bienvenue, Utilisateur!";
        ?>

        <br><br>
        <a href="deconnexion.php">Se déconnecter</a>
    </div>
</body>
</html>
