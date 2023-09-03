<?php
// Inclure le fichier de connexion à la base de données
require_once("connexion.php");
?>

<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["utilisateur_id"])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit;
}

// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    require_once("connexion.php");

    // Récupérer les données du formulaire
    $nom_produit_stock = $_POST["nom_produit_stock"];
    $prix_produit_stock = $_POST["prix_produit_stock"];
    $quantite_produit_stock = $_POST["quantite_produit_stock"];

    // Insérer les données dans la table produit
    $requete_stock = "INSERT INTO produit (nom_produit, prix_unitaire_produit, quantite_produit) VALUES (?, ?, ?)";
    $stmt = $connexion->prepare($requete_stock);
    $stmt->bind_param("sdi", $nom_produit_stock, $prix_produit_stock, $quantite_produit_stock);

    if ($stmt->execute()) {
        // Succès de l'insertion
        echo "Produit enregistré avec succès.";

        // Bouton de retour
        echo '<br><br><a href="gestion.php">Retour à la page de gestion</a>';
    } else {
        // Échec de l'insertion
        echo "Erreur lors de l'enregistrement du produit : " . $stmt->error;
    }

    // Fermer la connexion
    $connexion->close();
}
?>
