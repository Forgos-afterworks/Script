<?php
include_once "entity/Categorie.php";
include_once "entity/Produit.php";
include_once "entity/Declinaison.php";

class PrestashopRepository {

    private $connexion;

    public function __construct() {
        $this->connexion = new PDO('mysql:host=127.0.0.1;dbname=prestashop;charset=UTF8', "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //Donner la liste des catégories en français
    /**
     * @return Categorie[]
     */
    public function getAllCategories() :array {
        $request = $this->connexion->prepare("SELECT * FROM ps_category_lang WHERE id_lang = 1");
        $request->execute();
        $categories = $request->fetchAll(PDO::FETCH_ASSOC);
        return $this->categories($categories);
    }

    //Récupérer tous les produit d'une catégorie
    /**
     * @return Produit[]
     */
    public function getAllProduitsFromCategorie(Categorie $categorie) {
        $requestIdProduits = $this->connexion->prepare(
            "SELECT * 
                FROM ps_category_product INNER JOIN ps_product_lang ON ps_product_lang.id_product = ps_category_product.id_product
                WHERE id_category = :idCategorie AND id_lang = 1");
        $requestIdProduits->execute([":idCategorie" => $categorie->getId()]);
        $products = $requestIdProduits->fetchAll(PDO::FETCH_ASSOC);
        return $this->produits($products);
    }

    //Récupérer toute les déclinaisons d'un produit
    public function getAllDeclinaisonFromProduct(Produit $produit) {
        $request = $this->connexion->prepare(
            "SELECT DISTINCT pal.*
                        FROM ps_product_lang p
                        INNER JOIN ps_product_attribute pa ON p.id_product = pa.id_product
                        INNER JOIN ps_product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
                        INNER JOIN ps_attribute_lang pal ON pal.id_attribute = pac.id_attribute
                        WHERE pa.id_product = :id_produit AND pal.id_lang = 1"
        );
        $request->execute([":id_produit" => $produit->getId()]);
        $declinaisons = $request->fetchAll(PDO::FETCH_ASSOC);
        return $this->declinaison($declinaisons);
    }

    //Vérifier si la catégorie existe
    public function checkIfExistCat(Categorie $categorie) {
        $request = $this->connexion->prepare("SELECT * FROM ps_category_lang WHERE id_lang = 1 AND id_category = :id");
        $request->execute([":id" => $categorie->getId()]);
        $catBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($catBDD) > 0;
    }

    //Vérifier si le produit existe
    public function checkIfExistProd(Produit $produit) {
        $request = $this->connexion->prepare("SELECT * FROM ps_product_lang WHERE id_lang = 1 AND id_product = :id");
        $request->execute([":id" => $produit->getId()]);
        $prodBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($prodBDD) > 0;
    }

    //Vérifier si la declinaison existe
    public function checkIfExistDeclinaison(Declinaison $declinaison) {
        $request = $this->connexion->prepare("SELECT * FROM ps_attribute_lang WHERE id_lang = 1 AND id_attribute = :idDeclinaison");
        $request->execute([":idDeclinaison" => $declinaison->getId()]);
        $declinaisonBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($declinaisonBDD) > 0;
    }

    //Convertir en l'entitée Declinaison
    /**
     * @return Declinaison[]
     */
    private function declinaison(array $responsesBDD) {
        $tabDeclinaison = [];
        foreach ($responsesBDD as $response) {
            $declinaison = new Declinaison();
            $declinaison->setId($response["id_attribute"]);
            $declinaison->setNom($response["name"]);
            $tabDeclinaison[] = $declinaison;
        }
        return $tabDeclinaison;
    }

    //Convertir en l'entitée Categorie
    /**
     * @return Categorie[]
     */
    private function categories(array $responsesBDD) {
        $tabCategories = [];
        foreach ($responsesBDD as $response) {
            $categorie = new Categorie();
            $categorie->setId($response["id_category"]);
            $categorie->setName($response["name"]);
            $categorie->setDescription($response["description"]);
            $tabCategories[] = $categorie;
        }
        return $tabCategories;
    }

    //Convertir en l'entitée Produit
    /**
     * @return Produit[]
     */
    private function produits(array $responsesBDD) {
        $tabProduits = [];
        foreach ($responsesBDD as $response) {
            $produit = new Produit();
            $produit->setId($response["id_product"]);
            $produit->setIdCategorie($response["id_category"]);
            $produit->setDescription($response["description"]);
            $produit->setIdTVA(0);
            $produit->setNom($response["name"]);
            $tabProduits[] = $produit;
        }
        return $tabProduits;
    }
}