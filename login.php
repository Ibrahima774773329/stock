<?php
// Inclure le fichier de connexion à la base de données
require_once("connexion.php");

// ... Reste de votre code HTML ...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
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
        <h2>Connexion</h2>
        <?php
        session_start();

        // Vérifier si l'utilisateur est déjà connecté
        if (isset($_SESSION["utilisateur_id"])) {
            header("Location: gestion.php");
            exit;
        }

        // Traitement du formulaire de connexion
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $mot_de_passe = $_POST["mot_de_passe"];

            // Requête pour récupérer l'utilisateur par son email
            $requete = "SELECT id, mot_de_passe FROM utilisateur WHERE email = ?";
            $stmt = $connexion->prepare($requete);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $mot_de_passe_hash);
                $stmt->fetch();

                // Vérifier le mot de passe haché
                if (password_verify($mot_de_passe, $mot_de_passe_hash)) {
                    // Authentification réussie, enregistrer l'ID de l'utilisateur en session
                    $_SESSION["utilisateur_id"] = $id;
                    header("Location: gestion.php");
                    exit;
                } else {
                    echo "Mot de passe incorrect.";
                }
            } else {
                echo "Email incorrect.";
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="email">Email :</label>
            <input type="email" name="email" required><br><br>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br><br>

            <input type="submit" value="Se connecter">
        </form>
        <p>Pas encore inscrit ? <a href="index.php">S'inscrire</a></p>
    </div>
</body>
</html>
