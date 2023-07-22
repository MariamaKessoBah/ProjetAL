<?php
// Vérifier si l'ID de l'article à supprimer est présent dans la requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'])) {
    // Récupérer l'ID de l'article à supprimer depuis la requête POST
    $articleId = $_POST['article_id'];

    // Configuration de la base de données
    $host = 'localhost'; // Adresse du serveur de base de données
    $db = 'mglsi_news'; // Nom de la base de données
    $user = 'root'; // Nom d'utilisateur de la base de données
    $pass = ''; // Mot de passe de la base de données
    $charset = 'utf8mb4'; // Jeu de caractères

    // Connexion à la base de données avec PDO
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Mode de gestion des erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération des données par défaut
        PDO::ATTR_EMULATE_PREPARES => false, // Désactiver l'émulation des requêtes préparées
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $opt);

        // Supprimer l'article de la base de données
        $stmt = $pdo->prepare('DELETE FROM article WHERE article.id = ?');
        $stmt->execute([$articleId]);

        // Rediriger vers la page d'accueil
        header('Location: controller.php');
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, afficher le message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Si l'ID de l'article n'est pas présent dans la requête POST, rediriger vers la page d'accueil
    header('Location: controller.php');
    exit();
}
?>
