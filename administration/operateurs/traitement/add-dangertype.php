<?php
    session_start();  
    require_once "../../../database/db.php"; 
    var_dump(@$_POST);
 if (!empty(@$_POST))
        {
                $tdanger   = strip_tags($_POST['dangertype']);
                $req = $db->prepare('SELECT intitule FROM dangertype');
                $req->execute();
                $typedanger = $req->fetchAll();
                foreach ($typedanger as $bddanger) {
                    if (strcasecmp($bddanger['intitule'],$tdanger) == 0)
                    {
                        $_SESSION['echec']= 'is-invalid';
                        $_SESSION['infoechec'] ='cette valeur existe dejà, consulter la liste des Type';
                        header ("location:../be-tdanger.php");
                        break;
                    }
                    
                }
                //$bddanger = $typedanger['intitule'];
                //var_dump($typedanger);
                //var_dump(strcasecmp($bddanger,$tdanger) == 0);
                $newtype = [
                    'typeDanger' => $tdanger, 
                    'id'        => $_SESSION['id'],
                    'datemodif' => date("Y-m-d H:i:s"),
                    'dateajout' => date("Y-m-d H:i:s")
                ];
                $typeprepare=$db->prepare("INSERT  INTO dangertype(intitule, idUtilisateur, dateAjout, dateModification) VALUES (:typeDanger, :id, :dateajout, :datemodif)");
                
                $insert = $typeprepare->execute($newtype);
                echo '<hr> danger inserer';
                var_dump($insert);
                                //Enregistrement activité si mise à jour réussi.
                                if ($insert) {
                                    $newActivite = [
                                        ':activite'     => 'Enregistrement de type de danger',
                                        ':dateactivite' => date("Y-m-d H:i:s"),
                                        ':iduser'       => $_SESSION['id']
                                    ];
                                    var_dump($newActivite);
                                    $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
                                    if ($resultat) {
                                    var_dump($activite);
                                    $rActivite = $db->prepare($activite)->execute($newActivite);
                                    var_dump($rActivite);
                                    if ($rActivite) {
                                        $_SESSION['alerte']= "success";
                                        header("location:../be-tdanger.php");
                                    } else {
                                        $_SESSION['alerte']= "error";
                                        header("location:../be-tdanger.php");
                                    }
                                    
                                    }
                                    $_SESSION['alerte']= "error";
                                header ("location:../be-tdanger.php");
                                }
         
        } else
        {
            @$erreurType ='';
        }
?>