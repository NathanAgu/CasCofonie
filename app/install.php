<?php

use Signature\Service\Database;

require 'vendor/autoload.php';

$database = new Database();

createDatabase($database);
// deleteTables($database, true);

function createDatabase(Database $database) 
{
    echo "Création de la base de données\n";
    echo "Initialisation des tables : \n";
    $database->rawExecute(<<<SQL
    create table test_db.banners (
    id INT NOT NULL AUTO_INCREMENT,
    name CHAR(255) NOT NULL,
    extension CHAR(10) NOT NULL,
    mimeType CHAR(255) NOT NULL,
    createdAt DATETIME NOT NULL,
    updatedAt DATETIME NULL,
    link CHAR(255) NULL, 
    CONSTRAINT pk_banners PRIMARY KEY (id)
    );

    create table test_db.entite (
    id INT NOT NULL AUTO_INCREMENT,
    name CHAR(255) NOT NULL,
    address CHAR(255) NULL,
    numStandard CHAR(10) NULL,
    couleur CHAR(7) NULL, /* Prend en compte le format #ffffff */
    banniereRef INT, /* aura besoin d'une constrainte pour avoir une clé étragère */
    link CHAR(255) NULL, /*Modification potentielle sur la longueur */
    linkX CHAR(255) NULL,
    linkYoutube CHAR(255) NULL,
    linkGitHub CHAR(255) NULL,
    linkLinkedin CHAR(255) NULL,
    CONSTRAINT pk_entite PRIMARY KEY (id),
    CONSTRAINT fk_entite_banners FOREIGN KEY (banniereRef) REFERENCES banners(id)
    );

    create table test_db.users (
    id INT NOT NULL AUTO_INCREMENT,
    login CHAR(40) NOT NULL,
    password CHAR(255) NOT NULL,
    entite INT NOT NULL,
    isAdmin BOOL NOT NULL DEFAULT FALSE,
    isMarketing BOOL NOT NULL DEFAULT FALSE,
    name CHAR(40) NULL,
    firstName CHAR(40) NULL,
    poste CHAR(255) NULL,
    email CHAR(255) NULL,
    numPro CHAR(10) NULL,
    CONSTRAINT pk_users PRIMARY KEY (id),
    CONSTRAINT fk_users_entite FOREIGN KEY (entite) REFERENCES entite(id)
    );
    SQL);

    echo "Tables Créés avec succès\n";
    echo "\n";
    echo "Insertion des données par défaut\n";

    $database->rawExecute(<<<SQL
    INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType, link) VALUES("Makina Corpus", "png", "2024/05/12", "2024/05/12", "image/png","makina-corpus.com");
    INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType, link) VALUES("Makina Corpus Symfony", "png", "2024/05/12", "2024/05/12", "image/png","makina-corpus.com");
    INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType, link) VALUES("Makina Corpus Territoires", "png", "2024/05/12", "2024/05/12", "image/png","territoires.makina-corpus.com");
    INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType, link) VALUES("Makina Corpus Formations", "png", "2024/05/12", "2024/05/12", "image/png","makina-corpus.com/formations");
    INSERT INTO test_db.banners(name, extension, createdAt, updatedAt, mimeType, link) VALUES("Geotrek", "png", "2024/05/12", "2024/05/12", "image/png","geotrek.fr");

    INSERT INTO test_db.entite(name, banniereRef, address, numStandard, couleur, link, linkX, linkYoutube, linkGitHub, linkLinkedin) VALUES("Makina Corpus", 1, "11 rue Marchix\n44000 Nantes", "0251798080","#260b5b", "makina-corpus.com",  "https://x.com/makina_corpus", "https://www.youtube.com/user/makinacorpus", "https://github.com/makinacorpus/", "https://www.linkedin.com/company/makina-corpus/");
    INSERT INTO test_db.entite(name, banniereRef, address, numStandard, couleur, link) VALUES("Makina Corpus Formations", 2, "49 Gd Rue Saint-Michel\n31400 Toulouse", "0251798080","#ffd109", "makina-corpus.com/formations");
    INSERT INTO test_db.entite(name, banniereRef, address, numStandard, couleur, link) VALUES("Makina Corpus Territoires", 3, "11 rue Marchix\n44000 Nantes", "0251798080","#50af55", "territoires.makina-corpus.com");
    INSERT INTO test_db.entite(name, banniereRef, address, numStandard, couleur, link, linkX, linkYoutube, linkGitHub) VALUES("Geotrek", 4, "49 Gd Rue Saint-Michel\n31400 Toulouse", "0970332150","#b3ce0c", "geotrek.fr", "https://x.com/GeotrekCom", "https://x.com/GeotrekCom", "https://github.com/GeotrekCE");

    INSERT INTO test_db.users(id,login,password,name,firstName,poste,entite,isAdmin,isMarketing, numPro) VALUES(1,"adm","ac9689e2272427085e35b9d3e3e8bed88cb3434828b43b86fc0596cad4c6e270","Dudouet","Marius","Admin",1, true, true, "0123456789");

    SQL);
    echo "Données inserées avec succès\n";


    echo "L'identifiant administrateur est : 'adm'\nLe mot passe est : 'admin1234'\n";
}

function deleteTables(Database $database, bool $validate = false)
{   
    if ($validate) {
        echo "Suppression des tables de la base de donnée\n";
        $database->rawExecute(<<<SQL
            DROP TABLE test_db.users;
            DROP TABLE test_db.entite;
            DROP TABLE test_db.banners;
        SQL);
        echo "Tables supprimées avec succès\n";
    } else {
        echo "Si vous souhaitez supprimer les tables, mettez true en deuxième paramètre à l'appel de cette fonction\n";
    }
}
