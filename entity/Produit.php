<?php

class Produit {

    private int $id;
    private string $nom;
    private string $description;
    private float $prixUnitaire;
    private string $image;
    private int $nombreStock;
    private int $idCategorie;
    private int $idTVA;

    public function __construct() {
        $this->image = "";
        $this->nombreStock= 314;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom(): string {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrixUnitaire(): float {
        return $this->prixUnitaire;
    }

    /**
     * @param float $prixUnitaire
     */
    public function setPrixUnitaire(float $prixUnitaire): void {
        $this->prixUnitaire = $prixUnitaire;
    }

    /**
     * @return string
     */
    public function getImage(): string {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getNombreStock(): int {
        return $this->nombreStock;
    }

    /**
     * @param int $nombreStock
     */
    public function setNombreStock(int $nombreStock): void {
        $this->nombreStock = $nombreStock;
    }

    /**
     * @return int
     */
    public function getIdCategorie(): int {
        return $this->idCategorie;
    }

    /**
     * @param int $idCategorie
     */
    public function setIdCategorie(int $idCategorie): void {
        $this->idCategorie = $idCategorie;
    }

    /**
     * @return int
     */
    public function getIdTVA(): int {
        return $this->idTVA;
    }

    /**
     * @param int $idTVA
     */
    public function setIdTVA(int $idTVA): void {
        $this->idTVA = $idTVA;
    }
}