<?php
// Inclure le fichier de connexion à la base de données
require_once("connexion.php");

// ... Reste de votre code HTML ...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 0 auto;
            width: 80%;
            padding: 20px;
        }
        .form-container {
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }
        .form-container h2 {
            text-align: left;
        }
    </style>
</head>
<body>
<a href="deconnexion.php">Se déconnecter</a>
    <div class="container">
        <h2>Gestion des Produits</h2>

        <!-- Formulaire pour enregistrer les produits stockés -->
        <div class="form-container">
            <h2>Enregistrer les produits stockés</h2>
            <form method="post" action="traitement_stock.php">
                <label for="nom_produit_stock">Nom du produit :</label>
                <input type="text" name="nom_produit_stock" required><br><br>

                <label for="prix_produit_stock">Prix unitaire :</label>
                <input type="number" name="prix_produit_stock" required><br><br>

                <label for="quantite_produit_stock">Quantité en stock :</label>
                <input type="number" name="quantite_produit_stock" required><br><br>

                <input type="submit" value="Enregistrer dans le stock">
            </form>
        </div>

        <!-- Formulaire pour enregistrer les produits pris dans le stock -->
        <div class="form-container">
            <h2>Enregistrer les produits pris dans le stock</h2>
            <form method="post" action="traitement_pris.php">
                <label for="nom_produit_pris">Nom du produit :</label>
                <input type="text" name="nom_produit_pris" required><br><br>

                <label for="quantite_produit_pris">Quantité prise :</label>
                <input type="number" name="quantite_produit_pris" required><br><br>

                <input type="submit" value="Enregistrer la prise">
            </form>

        </div>

        <br><br>

        <!-- Affichage de la quantité des produits disponibles en stock -->
        <h2>Quantité des produits disponibles en stock</h2>
        <?php
        // Requête SQL pour obtenir la quantité totale des produits en stock
        $requete_stock_total = "SELECT SUM(quantite_produit) AS quantite_totale_stock FROM produit";
        $resultat_stock_total = $connexion->query($requete_stock_total);

        if ($resultat_stock_total === false) {
            die("Erreur lors de l'exécution de la requête SQL : " . $connexion->error);
        }

        if ($resultat_stock_total->num_rows > 0) {
            $row_total = $resultat_stock_total->fetch_assoc();
            $quantite_totale_stock = $row_total["quantite_totale_stock"];

            // Afficher la quantité totale des produits en stock
            echo "Quantité totale en stock : " . $quantite_totale_stock;
        } else {
            echo "Aucun produit en stock.";
        }

        // Requête SQL pour obtenir la quantité de chaque produit
        $requete_stock_produits = "SELECT nom_produit, quantite_produit FROM produit";
        $resultat_stock_produits = $connexion->query($requete_stock_produits);

        if ($resultat_stock_produits === false) {
            die("Erreur lors de l'exécution de la requête SQL : " . $connexion->error);
        }

        if ($resultat_stock_produits->num_rows > 0) {
            echo "<br><br>Liste des produits et leur quantité en stock :<br>";

            while ($row_produit = $resultat_stock_produits->fetch_assoc()) {
                $nom_produit = $row_produit["nom_produit"];
                $quantite_produit = $row_produit["quantite_produit"];

                echo "Produit : $nom_produit, Quantité en stock : $quantite_produit<br>";
            }
        } else {
            echo "Aucun produit en stock.";
        }
        ?>
        <br><br>

        <!-- Affichage de la liste des transactions -->
        <h2>Liste des Transactions</h2>
        <?php
        // Requête SQL pour obtenir la liste des transactions
        $requete_transactions = "SELECT t.date_transaction, u.nom, u.prenom, p.nom_produit, t.quantite
                                FROM transaction t
                                INNER JOIN utilisateur u ON t.id_utilisateur = u.id
                                INNER JOIN produit p ON t.id_produit = p.id_produit
                                ORDER BY t.date_transaction DESC";

        $resultat_transactions = $connexion->query($requete_transactions);

        if ($resultat_transactions === false) {
            die("Erreur lors de l'exécution de la requête SQL : " . $connexion->error);
        }

        if ($resultat_transactions->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Date de Transaction</th><th>Nom de l'Utilisateur</th><th>Prénom de l'Utilisateur</th><th>Nom du Produit</th><th>Quantité</th></tr>";

            while ($row_transaction = $resultat_transactions->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_transaction["date_transaction"] . "</td>";
                echo "<td>" . $row_transaction["nom"] . "</td>";
                echo "<td>" . $row_transaction["prenom"] . "</td>";
                echo "<td>" . $row_transaction["nom_produit"] . "</td>";
                echo "<td>" . $row_transaction["quantite"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "Aucune transaction enregistrée.";
        }
        ?>
    </div>
</body>
</html>
