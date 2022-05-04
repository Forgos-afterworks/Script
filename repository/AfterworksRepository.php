<?php
include_once "entity/Categorie.php";
include_once "entity/Produit.php";
include_once "entity/Declinaison.php";

class AfterworksRepository {

    private PDO $connexion;

    public function __construct() {
        $this->connexion = new PDO('mysql:host=127.0.0.1;dbname=afterworks;charset=UTF8', "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    //Vérifier si la categorie est dans afterworks
    public function checkCatExist(Categorie $categorie) :bool {
        $request = $this->connexion->prepare("SELECT * FROM categorie WHERE id_categorie = :idCategorie");
        $request->execute([":idCategorie" => $categorie->getId()]);
        $categorieBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($categorieBDD) > 0;
    }

    //Ajouter la categorie dans afterworks
    public function addCat(Categorie $categorie) :void {
        $request = $this->connexion->prepare("INSERT INTO categorie VALUES (:idCategorie, :nomCategorie, :descriptionCategorie)");
        $request->execute([
            ":idCategorie" => $categorie->getId(),
            ":nomCategorie" => $categorie->getName(),
            ":descriptionCategorie" => $categorie->getDescription()]);
    }

    //Vérifier si le produit est dans afterworks
    public function checkProduitExist(Produit $produit) :bool {
        $request = $this->connexion->prepare("SELECT * FROM produit WHERE id_produit = :idProduit");
        $request->execute([":idProduit" => $produit->getId()]);
        $produitBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($produitBDD) > 0;
    }

    //Ajouter le produit à afterworks
    public function addProduct(Produit $produit) :void {
        $request = $this->connexion->prepare("INSERT INTO produit VALUES (:id, :nom, :description, :PU, :image, :nbrStock, :idCat, :idTVA)");
        $request->execute([
            ":id" => $produit->getId(),
            ":nom" => $produit->getNom(),
            ":description" => $produit->getDescription(),
            ":PU" => $produit->getPrixUnitaire(),
            ":image" => $produit->getImage(),
            ":nbrStock" => $produit->getNombreStock(),
            ":idCat" => $produit->getIdCategorie(),
            ":idTVA" => $produit->getIdTVA()]);
    }

    //Vérifier qu'une déclinaison existe
    public function checkIfExistDeclinaison(Declinaison $declinaison) :bool {
        $request = $this->connexion->prepare("SELECT * FROM declinaison WHERE id_declinaison = :id");
        $request->execute([":id" => $declinaison->getId()]);
        $declinaisonBDD = $request->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($declinaisonBDD) > 0;
    }

    //Ajouter une déclinaison à afterworks
    public function addDeclinaison(Declinaison $declinaison) :void {
        $request = $this->connexion->prepare("INSERT INTO declinaison VALUES (:id, :nom, :description)");
        $request->execute([
            ":id" => $declinaison->getId(),
            ":nom" => $declinaison->getNom(),
            ":description" => $declinaison->getDescription()]);
    }

    //Ajouter le liens entre declinaison et produit
    public function addLinkDeclinaisonProduit(Declinaison $declinaison, Produit $produit):void {
        $request = $this->connexion->prepare("INSERT INTO produit_declinaison VALUES (:idProduit, :idDeclinaison)");
        $request->execute([
            "idProduit" => $produit->getId(),
            "idDeclinaison" => $declinaison->getId()
        ]);
    }

    //Supprimer une categorie
    public function deleteCategorie(Categorie $categorie):void {
        $requestDelProdDeclinaison = $this->connexion->prepare("
            DELETE produit_declinaison.* 
            FROM produit INNER JOIN produit_declinaison ON produit.id_produit = produit_declinaison.id_produit
            WHERE id_categorie = :idCategorie");
        $requestDelProdDeclinaison->execute([":idCategorie" => $categorie->getId()]);

        $requestDelProd = $this->connexion->prepare("
            DELETE produit.* 
            FROM produit
            WHERE id_categorie = :idCategorie");
        $requestDelProd->execute([":idCategorie" => $categorie->getId()]);

        $requestDelCat = $this->connexion->prepare("
            DELETE categorie.* 
            FROM categorie
            WHERE id_categorie = :idCategorie");
        $requestDelCat->execute([":idCategorie" => $categorie->getId()]);
    }

    //Supprimer un produit
    public function deleteProduit(Produit $produit):void {
        $requestDelProdDeclinaison = $this->connexion->prepare("
            DELETE  
            FROM produit_declinaison
            WHERE id_produit = :idProduit");
        $requestDelProdDeclinaison->execute([":idProduit" => $produit->getId()]);

        $requestDelProd = $this->connexion->prepare("
            DELETE 
            FROM produit
            WHERE id_produit = :idProduit");
        $requestDelProd->execute([":idProduit" => $produit->getId()]);
    }

    //Supprimer un produit
    public function deleteDeclinaison(Declinaison $declinaison):void {
        $requestDelProdDeclinaison = $this->connexion->prepare("
            DELETE  
            FROM produit_declinaison
            WHERE id_declinaison = :idDeclinaison");
        $requestDelProdDeclinaison->execute([":idDeclinaison" => $declinaison->getId()]);

        $requestDelProd = $this->connexion->prepare("
            DELETE 
            FROM declinaison
            WHERE id_declinaison = :idDeclinaison");
        $requestDelProd->execute([":idDeclinaison" => $declinaison->getId()]);
    }

    //Récupérer tous les produit d'une categorie
    /**
     * @return Produit[]
     */
    public function getAllProduitsFromCategorie(Categorie $categorie) {
        $requestIdProduits = $this->connexion->prepare(
            "SELECT * 
                FROM produit
                WHERE id_categorie = :idCategorie");
        $requestIdProduits->execute([":idCategorie" => $categorie->getId()]);
        $products = $requestIdProduits->fetchAll(PDO::FETCH_ASSOC);
        return $this->produits($products);
    }

    //Récupérer toutes les déclinaisons d'un produit
    /**
     * @return Declinaison[]
     */
    public function getAllDeclinaisonsFromProduct(Produit $produit) {
        $requestIdProduits = $this->connexion->prepare(
            "SELECT declinaison.* 
                FROM produit_declinaison INNER JOIN declinaison ON produit_declinaison.id_declinaison = declinaison.id_declinaison
                WHERE id_produit = :idProduit");
        $requestIdProduits->execute([":idProduit" => $produit->getId()]);
        $declinaisons = $requestIdProduits->fetchAll(PDO::FETCH_ASSOC);
        return $this->declinaison($declinaisons);
    }

    //Récupérer toutes les déclinaisons
    /**
     * @return Declinaison[]
     */
    public function getAllDeclinaisons() {
        $requestIdProduits = $this->connexion->prepare(
            "SELECT declinaison.* 
                FROM declinaison");
        $requestIdProduits->execute();
        $declinaisons = $requestIdProduits->fetchAll(PDO::FETCH_ASSOC);
        return $this->declinaison($declinaisons);
    }

    /**
     * @return Categorie[]
     */
    public function getAllCategories() :array {
        $request = $this->connexion->prepare("SELECT * FROM categorie");
        $request->execute();
        $categories = $request->fetchAll(PDO::FETCH_ASSOC);
        return $this->categories($categories);
    }

    //Convertir en l'entitée Categorie
    /**
     * @return Categorie[]
     */
    private function categories(array $responsesBDD) {
        $tabCategories = [];
        foreach ($responsesBDD as $response) {
            $categorie = new Categorie();
            $categorie->setId($response["id_categorie"]);
            $categorie->setName($response["nom_categorie"]);
            $categorie->setDescription($response["description_categorie"]);
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
            $produit->setId($response["id_produit"]);
            $produit->setIdCategorie($response["id_categorie"]);
            $produit->setDescription($response["description_produit"]);
            $produit->setIdTVA(1);
            $produit->setNom($response["nom_produit"]);
            $tabProduits[] = $produit;
        }
        return $tabProduits;
    }

    //Convertir en l'entitée Declinaison
    /**
     * @return Declinaison[]
     */
    private function declinaison(array $responsesBDD) {
        $tabDeclinaison = [];
        foreach ($responsesBDD as $response) {
            $declinaison = new Declinaison();
            $declinaison->setId($response["id_declinaison"]);
            $declinaison->setNom($response["nom_declinaison"]);
            $tabDeclinaison[] = $declinaison;
        }
        return $tabDeclinaison;
    }
}