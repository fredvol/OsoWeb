<?php
/**
 * Oso Website 
 *
 * Generated and unique string ID format zz999zz
 *
 * Requires: PHP 5.5
 */


try
    {
            $bdd = new PDO('mysql:host=localhost;dbname=oso_test;charset=utf8', 'root', 'mysqlfred', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }
    // TODO : Check if connexion with dbb is ok
    $id2print="No Id Valid";
    $Idfound = FALSE;
    $i=0;

while (!$Idfound and $i <= 5) {
    $possibleid=chr(rand(97, 122)).chr(rand(97, 122)).rand(0,9).rand(0,9).rand(0,9).rand(0,9).chr(rand(97, 122)).chr(rand(97, 122));

    $req = $bdd->prepare('SELECT COUNT(id) FROM position  WHERE user= :user' );
    $req->execute(array('user' => $possibleid));
    $donnees = $req->fetch();


    if ($donnees[0]==0){
         $Idfound = TRUE;
         $id2print=$possibleid;
    }
    $i=$i+1;
 }
 
 $req->closeCursor();


 echo  $id2print;
 
?>
