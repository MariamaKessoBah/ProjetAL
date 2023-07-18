<?php
require_once 'model.php';

// Création d'une instance de la classe Database
$db = new Database();

// Récupération de l'identifiant de la catégorie depuis la requête GET
$categorieId = $_GET['categorie'] ?? null;

// Création d'une instance de la classe Category
$category = new Category($db);

// Récupération des catégories
$categories = $db->getCategories();

// Récupération des articles
$articles = $db->getArticles($categorieId);

// Inclure la vue
require_once 'index.php';
