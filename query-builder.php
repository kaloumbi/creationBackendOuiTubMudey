<?php 
use App\Entity\User;

require_once "bootstrap.php";

$qb = $em->createQueryBuilder();

//$id = $argv[1];
$id = $argv[1];
$full_name = $argv[2];

//Afficher tous les utilisateurs là où l'identifiant est egale à 2
/* $qb->select("u") //select * ==> pour recuperer les informations
    ->from(User::class,"u") //usage d'alias pour notre classe User ==> pour indiquer la provenance de ces infos à recuperer
    ->where("u.id = :id") // ==> pour préciser les contraintes de la selection ou la recuperation
    ->setParameter("id", 2) // ==> mettre les informations qu'on souhaitent integrer dans les parametres
; */

/* //Requette de suppression
$qb->delete(User::class, "u")
    ->where("u.id = :id")
    ->setParameter(":id", $id)
; */

// Requette de selection avec plusieurs critères !
/* $qb->select("u") //select * ==> pour recuperer les informations
    ->from(User::class,"u") //usage d'alias pour notre classe User ==> pour indiquer la provenance de ces infos à recuperer
    ->where("u.full_name = :full_name") // ==> pour préciser les contraintes de la selection ou la recuperation
    ->andWhere("u.email = :email") // ==> pour préciser les contraintes de la selection ou la recuperation
    ->setParameter(":full_name", "Davion White")
    ->setParameter(":email", "irma.waelchi@funk.com")
; */

/* $qb->select("u") //select * ==> pour recuperer les informations
    ->from(User::class,"u") //usage d'alias pour notre classe User ==> pour indiquer la provenance de ces infos à recuperer
    ->where("u.id > :ids ")
    ->setParameter(":ids", 90)
    ->setFirstResult(15)
    ->setMaxResults(3)
; */

$qb->update(User::class, "u")
    ->set("u.full_name", "?1")
    ->where("u.id = ?2")
    ->setParameter(1, $full_name)
    ->setParameter(2, $id)

;

$query = $qb->getQuery();

//DQL: Doctrine Query Language
echo    $query->getDQL()."\n";
echo    $query->execute(); // usage en cas de suppression ou de mise à jour !

//$user = $query->getOneOrNullResult();
$users = $query->getResult();
/* $result = [];
foreach ($users as $user) {
    # code...

    $result[] = $user->getFullName();
} */

//echo $user->getFullName();
//var_dump($result);