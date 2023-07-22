<?php
session_start();

// Configuration de la base de données
$host = 'localhost'; // Adresse du serveur de base de données
$db   = 'mglsi_news'; // Nom de la base de données
$user = 'root'; // Nom d'utilisateur de la base de données
$pass = ''; // Mot de passe de la base de données
$charset = 'utf8mb4'; // Jeu de caractères

// Connexion à la base de données avec PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mode de gestion des erreurs
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération des données par défaut
    PDO::ATTR_EMULATE_PREPARES   => false, // Désactiver l'émulation des requêtes préparées
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Récupération des catégories depuis la base de données
$stmt = $pdo->query('SELECT * FROM Categorie');
$categories = $stmt->fetchAll();

// Récupération de l'identifiant de la catégorie depuis la requête GET
$categorieId = $_GET['categorie'] ?? null;

// Vérifier si l'utilisateur est authentifié
$isLoggedIn = false;
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    $isLoggedIn = true;
}

// Récupération des articles en fonction de la catégorie sélectionnée (ou tous les articles si aucune catégorie n'est sélectionnée)
if ($categorieId && $isLoggedIn) {
    $stmt = $pdo->prepare('SELECT * FROM Article WHERE categorie = ? AND contenu IS NOT NULL AND contenu != ""');
    $stmt->execute([$categorieId]);
    $articles = $stmt->fetchAll();
} else {
    $stmt = $pdo->query('SELECT * FROM Article WHERE contenu IS NOT NULL AND contenu != ""');
    $articles = $stmt->fetchAll();
}

function getCategoryLabel($categoryId) {
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "mglsi_user", "passer", "mglsi_news");
    if (!$connexion) {
        die("Erreur de connexion à la base de données: " . mysqli_connect_error());
    }

    // Récupération du libellé de la catégorie
    $query = "SELECT libelle FROM Categorie WHERE id = ?";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $categoryId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['libelle'];
    }

    // Fermeture de la requête et de la connexion à la base de données
    mysqli_stmt_close($stmt);
    mysqli_close($connexion);
    return "Catégorie inconnue";
}

// Traitement du formulaire d'authentification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérification des informations d'identification (vous pouvez remplacer cette vérification par celle que vous souhaitez)
    if ($email === 'votre@email.esp.sn' && $password === 'votre_mot_de_passe') {
        $_SESSION['loggedIn'] = true;
        $isLoggedIn = true;
    } else {
        echo '<script>alert("Identifiants incorrects. Veuillez réessayer.");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon site d'actualités</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .navbar .nav-link {
            color: green;
        }
        
        header {
            border: 2px solid green;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        h1 {
            margin: 10px 5px 20px 5px;
        }
        
        h2 {
            text-align: left;
            margin: 10px 10px 20px 5px;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-title {
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: bold;
        }
        
        .card-text {
            margin-bottom: 20px;
        }
        
        .text-muted {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Site d'actualités</h1>
            <nav class="navbar navbar-expand-lg">
                <div class="navbar-collapse">
                    <ul class="navbar-nav">
                        <?php foreach ($categories as $categorie) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal"><?php echo $categorie['libelle']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>
        </header>
        <h2>La liste des articles disponibles</h2>
        <?php if ($isLoggedIn) : ?>
            <?php foreach ($articles as $article) : ?>
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title"><?php echo $article['titre']; ?></h2>
                        <p class="card-text"><strong>Catégorie : </strong><?php echo getCategoryLabel($article['categorie']); ?></p>
                        <p class="card-text"><?php echo $article['contenu']; ?></p>
                        <p class="text-muted"><em>Date de création : <?php echo $article['dateCreation']; ?></em></p>
                        <p class="text-muted"><em>Date de modification : <?php echo $article['dateModification']; ?></em></p>
                            <!-- Boutons Ajouter, Modifier et Supprimer -->
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">Ajouter</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal" data-articleid="<?php echo $article['id']; ?>" data-titre="<?php echo $article['titre']; ?>" data-categorie="<?php echo $article['categorie']; ?>" data-contenu="<?php echo $article['contenu']; ?>">Modifier</button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" data-articleid="<?php echo $article['id']; ?>">Supprimer</button>
                        </div>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Vous devez vous connecter pour accéder à la liste des articles.</p>
        <?php endif; ?>
    </div>

    <!-- Modal d'authentification -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="controller.php" method="post">
                        <div class="form-group">
                            <label for="email">Adresse Email (esp.sn):</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Modal d'ajout d'article -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter un article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire d'ajout d'article -->
                <form action="add_article.php" method="post">
                    <div class="form-group">
                        <label for="titre">Titre :</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Catégorie :</label>
                        <select class="form-control" id="categorie" name="categorie" required>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['libelle']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="contenu">Contenu :</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modification d'article -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modifier l'article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de modification d'article -->
                <form action="edit_article.php" method="post">
                    <input type="hidden" name="article_id" id="edit_article_id">
                    <div class="form-group">
                        <label for="edit_titre">Titre :</label>
                        <input type="text" class="form-control" id="edit_titre" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_categorie">Catégorie :</label>
                        <select class="form-control" id="edit_categorie" name="categorie" required>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['libelle']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_contenu">Contenu :</label>
                        <textarea class="form-control" id="edit_contenu" name="contenu" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression d'article -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Supprimer l'article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulaire de suppression d'article -->
                <form action="delete_article.php" method="post">
                    <input type="hidden" name="article_id" id="delete_article_id">
                    <p>Êtes-vous sûr de vouloir supprimer cet article ?</p>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
