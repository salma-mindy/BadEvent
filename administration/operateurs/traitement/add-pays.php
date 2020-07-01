<?php
   session_start();
   require_once "../../../database/db.php";
   var_dump($_POST);
   var_dump($_FILES["image"]["name"]);
   if (!empty($_POST)) {
        $nomlieu          =strip_tags($_POST['nomlieu']);
        $descriptionlieu  =strip_tags($_POST['descriptionlieu']);
        $image            =strip_tags($_FILES["image"]["name"]);
        $imagePath        = '../image/pays/'. basename($image);
        $imageExtension   = pathinfo($imagePath,PATHINFO_EXTENSION);
        $isSuccess        = true;
        $isUploadSuccess  = false;
  
        if (empty($nomlieu)) {
                $_SESSION['errorlieu'] ='is-invalid';
                $isSuccess = false;
                $_SESSION['lieuInvalid']="";
            }   
        if (empty($descriptionlieu)) {
                $_SESSION['errordesclieu'] ='is-invalid';
                $isSuccess = false;
                $_SESSION['desclieuInvalid']="";
            }
        if(!empty($image)) 
            {
                $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" && $imageExtension != "PNG" && $imageExtension != "JPG" && $imageExtension != "JPEG" && $imageExtension != "GIF") 
                {
                    $_SESSION['imageInvalid'] = "is-invalid";
                    $_SESSION['imageError'] = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
                    echo "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif";
                    $isUploadSuccess = false;
                }
            if($_FILES["image"]["size"] > 5000000) 
                {
                    $_SESSION['imageInvalid']= "is-invalid";
                    $_SESSION['imageError'] = "Le fichier ne doit pas depasser les 500KB";
                    echo "Le fichier ne doit pas depasser les 500KB";
                    $isUploadSuccess = false;
                }
            if($isUploadSuccess) 
                {
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath ) )
                    {
                        $_SESSION['imageInvalid'] = "is-invalid";
                        $_SESSION['imageError'] = "Il y a eu une erreur lors de l'upload";
                        echo "Il y a eu une erreur lors de l'upload";
                        $isUploadSuccess = false;
                    } 
                } 
            }
            if ($isSuccess && $isUploadSuccess) {
                 $newlieu = [
                     'nomLieu'              => $nomlieu,
                     'descriptionlieu'      => $descriptionlieu,
                     'lat'                  => $_POST['lat'],
                     'lng'                  => $_POST['lng'],
                     'imagelieu'            => $image,
                     'dataajout'            => date("Y-m-d H:i:s"),
                     'dernieremodif'        => date("Y-m-d H:i:s"),
                     'idUtilisateur'        => $_SESSION['id']
                 ];
                $insertlieu = "INSERT INTO pays (nom, descriptionPays, lat, lng, img, dateAjout, dateModification, idUtilisateur) VALUES (:nomLieu, :descriptionlieu, :lat, :lng,  :imagelieu, :dataajout, :dernieremodif, :idUtilisateur)";
                $resultat = $db->prepare($insertlieu)->execute($newlieu);
                //var_dump($resultat);
                $newActivite = [
                    ':activite'     => 'Ajout pays',
                    ':dateactivite' => date("Y-m-d H:i:s"),
                    ':iduser'       => $_SESSION['idUtilisateur']
                ];
                var_dump($newActivite);
                if ($resultat) {
                    /*$activite = "INSERT  INTO activite (nomActivite, dateActivite, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
                var_dump($activite);
                $rActivite = $db->prepare($activite)->execute($newActivite);
                var_dump($rActivite);
               ($resultat) ? header ("location:../ajout-pays.php") : header ("location:../ajout-pays.php?operation=echec") ;*/
             header ("location:../ajouter-pays.php");

                }
               
            } else {
                 header("location:../ajouter-pays.php");
            }

            
        }
?>