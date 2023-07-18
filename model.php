<?php
class Database
{
    private $host = 'localhost';
    private $db = 'mglsi_news';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $opt);
    }

    public function getCategories()
    {
        $stmt = $this->pdo->query('SELECT * FROM Categorie');
        return $stmt->fetchAll();
    }

    public function getArticles($categorieId)
    {
        if ($categorieId) {
            $stmt = $this->pdo->prepare('SELECT * FROM Article WHERE categorie = ? AND contenu IS NOT NULL AND contenu != ""');
            $stmt->execute([$categorieId]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->query('SELECT * FROM Article WHERE contenu IS NOT NULL AND contenu != ""');
            return $stmt->fetchAll();
        }
    }
}

class Category
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getCategoryLabel($categoryId)
    {
        $categories = $this->db->getCategories();
        foreach ($categories as $categorie) {
            if ($categorie['id'] == $categoryId) {
                return $categorie['libelle'];
            }
        }
        return "Catégorie inconnue";
    }
}
