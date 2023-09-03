<?php
// Inclure le fichier de connexion à la base de données
require_once("connexion.php");

// ... Reste de votre code HTML ...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
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
        <h2>Inscription</h2>
        <?php
        // Traitement du formulaire d'inscription
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $email = $_POST["email"];
            $mot_de_passe = $_POST["mot_de_passe"];

            // Hacher le mot de passe (utilisez une meilleure méthode de hachage en production)
            $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $requete = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
            $stmt = $connexion->prepare($requete);
            $stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe_hache);

            if ($stmt->execute()) {
                echo "Inscription réussie.";
            } else {
                echo "Erreur lors de l'inscription : " . $stmt->error;
            }
        }
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" required><br><br>

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" required><br><br>

            <label for="email">Email :</label>
            <input type="email" name="email" required><br><br>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" required><br><br>

            <input type="submit" value="S'inscrire">
            <p>Déjà un utilisateur ? <a href="login.php">Se connecter</a></p>
        </form>
    </div>
</body>
</html>
