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
    $nom_produit_pris = $_POST["nom_produit_pris"];
    $quantite_produit_pris = $_POST["quantite_produit_pris"];

    // Recherchez l'ID du produit en fonction de son nom (à adapter à votre structure)
    $requete_recherche_produit = "SELECT id_produit, quantite_produit FROM produit WHERE nom_produit = ?";
    $stmt = $connexion->prepare($requete_recherche_produit);
    $stmt->bind_param("s", $nom_produit_pris);
    $stmt->execute();
    $resultat = $stmt->get_result();

    if ($resultat->num_rows > 0) {
        $row = $resultat->fetch_assoc();
        $produit_id = $row["id_produit"];
        $quantite_actuelle = $row["quantite_produit"];

        // Vérifier si la quantité prise ne dépasse pas la quantité actuelle
        if ($quantite_produit_pris <= $quantite_actuelle) {
            // Mettre à jour la quantité dans la base de données
            $nouvelle_quantite = $quantite_actuelle - $quantite_produit_pris;
            $requete_mise_a_jour = "UPDATE produit SET quantite_produit = ? WHERE id_produit = ?";
            $stmt = $connexion->prepare($requete_mise_a_jour);
            $stmt->bind_param("di", $nouvelle_quantite, $produit_id);
            $stmt->execute();

            // Insérer les données dans la table transaction
            $utilisateur_id = $_SESSION["utilisateur_id"];
            $date_transaction = date("Y-m-d H:i:s"); // Date actuelle

            $requete_pris = "INSERT INTO transaction (date_transaction, id_utilisateur, id_produit, quantite) VALUES (?, ?, ?, ?)";
            $stmt = $connexion->prepare($requete_pris);
            $stmt->bind_param("siid", $date_transaction, $utilisateur_id, $produit_id, $quantite_produit_pris);

            if ($stmt->execute()) {
                // Succès de l'insertion
                echo "Produit pris enregistré avec succès.";

                // Bouton de retour
                echo '<br><br><a href="gestion.php">Retour à la page de gestion</a>';
            } else {
                // Échec de l'insertion
                echo "Erreur lors de l'enregistrement du produit pris : " . $stmt->error;
            }
        } else {
            echo "La quantité prise dépasse la quantité actuelle en stock.";
        }
    } else {
        echo "Le produit spécifié n'existe pas.";
    }

    // Fermer la connexion
    $connexion->close();
}
?>
