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
                         <!-- Boutons Ajouter, Modifier et Supprimer -->
                          <!-- Boutons Ajouter, Modifier et Supprimer -->
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#addModal">Ajouter</button>
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#editModal" data-articleid="<?php echo $article['id']; ?>" data-titre="<?php echo $article['titre']; ?>" data-categorie="<?php echo $article['categorie']; ?>" data-contenu="<?php echo $article['contenu']; ?>">Modifier</button>
                            <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#deleteModal" data-articleid="<?php echo $article['id']; ?>">Supprimer</button>
                        </div>
                </div>
            </div>
        <?php endforeach; ?>
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
