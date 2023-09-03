<?php
// Informations de connexion à la base de données
$serveur = "localhost"; // Adresse du serveur MySQL (généralement localhost)
$utilisateur = "root"; // Nom d'utilisateur MySQL
$mot_de_passe = ""; // Mot de passe MySQL
$base_de_donnees = "gestion_produits"; // Nom de la base de données que vous avez créée

// Créer une connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("La connexion à la base de données a échoué : " . $connexion->connect_error);
}
?>
