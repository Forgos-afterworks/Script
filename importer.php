<?php
include_once "repository/AfterworksRepository.php";
include_once "repository/PrestashopRepository.php";

$prestaBDD = new PrestashopRepository();
$afterBDD = new AfterworksRepository();

//Je récupère toutes les catégories de prestashop et de afterworks
$categoriesPresta = $prestaBDD->getAllCategories();
$categoriesAfter = $afterBDD->getAllCategories();

//Je parcours toute les catégories dans afterworks
foreach ($categoriesAfter as $catgorieAfter) {

    //Je vérifie si la catégorie est aussi dans prestashop
    if (!$prestaBDD->checkIfExistCat($catgorieAfter)) {

        //Je demande si l'utilisateur veux supprimer la catégorie
        echo "La catégorie " . $catgorieAfter->getName() . " n’existe pas dans prestashop.\n Voulez vous la supprimer ? O/n";
        $response = readline();

        //Il veux la supprimer
        if ($response != "n") {
            //Suppression de la catégorie
            $afterBDD->deleteCategorie($catgorieAfter);
            continue;
        }

        //Il ne veux pas la supprimer, on vas regarder pour les produits
        //On récupère les produits associés à la catégorie
        $produitsAfter = $afterBDD->getAllProduitsFromCategorie($catgorieAfter);

        //Je parcours la liste de tous mes produits
        foreach ($produitsAfter as $produitAfter) {
            //Je vérifie si le produit est aussi dans prestashop
            if (!$prestaBDD->checkIfExistProd($produitAfter)) {
                //Je demande si l'utilisateur veux supprimer le produit
                echo $catgorieAfter->getName() . " ------ le produit " . $produitAfter->getNom() . " n’existe pas dans prestashop.\n Voulez vous le supprimer ? O/n";
                $response = readline();

                //Il veux le supprimer
                if ($response != "n") {
                    //Supression du produit
                    $afterBDD->deleteProduit($produitAfter);
                    continue;
                }
            }
            //Il ne veux pas le supprimer on vas s'occuper des déclinaisons
            //On récupère les déclinaisons du produit
            $declinaisonsAfter = $afterBDD->getAllDeclinaisonsFromProduct($produitAfter);

            //Je parcours les déclinaisons
            foreach ($declinaisonsAfter as $declinaisonAfter) {
                //Je vérifie si la déclinaison existe dans prestashop
                if (!$prestaBDD->checkIfExistDeclinaison($declinaisonAfter)) {
                    //Je demande si l'utilisateur veux supprimer la déclinaison
                    echo $catgorieAfter->getName() . " ------ " . $produitAfter->getNom() . " ------- la déclinaison " . $declinaisonAfter->getNom() . " n’existe pas dans prestashop.\n Voulez vous la supprimer ? O/n";
                    $response = readline();

                    //Il veut supprimer la declinaison
                    if ($response != "n") {
                        //Supression de la déclinaison
                        $afterBDD->deleteDeclinaison($declinaisonAfter);

                    }
                }
            }
        }
    }
}

//On récupère les déclinaisons du produit
$declinaisonsAfter = $afterBDD->getAllDeclinaisons();
//Je parcours les déclinaisons
foreach ($declinaisonsAfter as $declinaisonAfter) {
    //Je vérifie si la déclinaison existe dans prestashop
    if (!$prestaBDD->checkIfExistDeclinaison($declinaisonAfter)) {
        //Je demande si l'utilisateur veux supprimer la déclinaison
        echo "La déclinaison " . $declinaisonAfter->getNom() . " n’existe pas dans prestashop.\n Voulez vous la supprimer ? O/n";
        $response = readline();

        //Il veut supprimer la declinaison
        if ($response != "n") {
            //Supression de la déclinaison
            $afterBDD->deleteDeclinaison($declinaisonAfter);

        }
    }
}


//Je parcours les toutes catégories dans prestashop
foreach($categoriesPresta as $categoriePresta) {

    //Je vérifie si la catégorie est dans afterworks
    if (!$afterBDD->checkCatExist($categoriePresta)) {

        //Je demande si l'utilisateur veux l'importer la catégorie
        echo "La catégorie " . $categoriePresta->getName() . " n’existe pas.\n Voulez vous l’importer ? O/n";
        $response = readline();

        //Il veux l'importée
        if ($response != "n") {
            //Ajouter la catégorie
            $afterBDD->addCat($categoriePresta);

        } else {
            //Il ne veux pas importer la catégorie on passe à la suivante
            continue;
        }
    } else {
        //La catégorie existe on vas quand même vérifier que tout les produits aussi
    }

    //Je récupère tout les produit de la catégorie de prestashop
    $produitsPresta = $prestaBDD->getAllProduitsFromCategorie($categoriePresta);

    //Je parcours tout les produits de la catégorie
    foreach($produitsPresta as $produit) {

        //Je vérifie si le produit est dans afterworks
        if (!$afterBDD->checkProduitExist($produit)) {

            //Je demande si l'utilisateur veux l'importer
            echo $categoriePresta->getName() . " -- Le produit " . $produit->getNom() . " n’existe pas.\n Voulez vous l’importer ? O/n";
            $response = readline();

            //Il veux l'importée
            if ($response != "n") {
                //L'utilisateur doit définir le prix unitaire
                echo $categoriePresta->getName() . " ----  Quel prix voulez-vous donner au produit " . $produit->getNom();
                $reponse = readline();
                if (empty($response)) $produit->setPrixUnitaire(0);
                else $produit->setPrixUnitaire((float)$response);

                //Ajouter le produit
                $afterBDD->addProduct($produit);

            } else {
                //Il ne veut pas importer le produit on passe à au suivant
                continue;
            }

            //On récupère toutes les déclinaisons d'un produit
            $declinaisonPresta = $prestaBDD->getAllDeclinaisonFromProduct($produit);

            //Pour chaque declianaison d'un produit
            foreach ($declinaisonPresta as $declinaison) {
                //Je vérifie si la déclinaison existe dans afterworks
                if (!$afterBDD->checkIfExistDeclinaison($declinaison)) {
                    //Je demande si l'utilisateur veux l'importer
                    echo " -- La déclinaison " . $declinaison->getNom() . " n’existe pas.\n Voulez vous l’importer ? O/n";
                    $response = readline();

                    //Il veux l'importée
                    if ($response != "n") {
                        //Ajouter la déclinaison
                        $afterBDD->addDeclinaison($declinaison);

                        //Faire le liens entre la déclinaison et le produit
                        $afterBDD->addLinkDeclinaisonProduit($declinaison, $produit);

                    } else {
                        //Il ne veut pas importer la déclinaison on passe à la suivante
                        continue;
                    }
                }
            }

        } else {
            //Le produit existe dans la BDD on passe au suivant
            continue;
        }
    }
}