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
                                <a class="nav-link" href="?categorie=<?php echo $categorie['id']; ?>"><?php echo $categorie['libelle']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>
        </header>
        <h2>La liste des articles disponibles</h2>
        <?php foreach ($articles as $article) : ?>
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title"><?php echo $article['titre']; ?></h2>
                    <p class="card-text"><strong>Catégorie : </strong><?php echo $category->getCategoryLabel($article['categorie']); ?></p>
                    <p class="card-text"><?php echo $article['contenu']; ?></p>
                    <p class="text-muted"><em>Date de création : <?php echo $article['dateCreation']; ?></em></p>
                    <p class="text-muted"><em>Date de modification : <?php echo $article['dateModification']; ?></em></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
