<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;

class ChasserController extends Controller
{

    public function chasserAction()
    {
// L'utilisateur est bien en ROLE_USER
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
// On récupère l'utilisateur loggé
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources

            $ressourcesViande = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 3, 'utilisateurss' => $user->getId())); // On récupère l'item "3" dans les ressources
            $ressourcesOeuf = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 4, 'utilisateurss' => $user->getId())); // On récupère l'item "4" dans les ressources
            $ressourcesChampignon = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 6, 'utilisateurss' => $user->getId())); // ...
            $ressourcesPeau = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 10, 'utilisateurss' => $user->getId())); // ...

            // On récupère la liste des items pour modifier les rand().
            $ressourcesArc = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 8, 'utilisateurss' => $user->getId())); // ...
            $ressourcesFleche = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 9, 'utilisateurss' => $user->getId())); // ...
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // ...

            $itemsViande = $em->getRepository('CoeurBundle:Items')->findOneById('3'); // On récupère les infos de l'item "viande"
            $itemsOeuf = $em->getRepository('CoeurBundle:Items')->findOneById('4'); // On récupère les infos de l'item "oeuf"
            $itemsChampignon = $em->getRepository('CoeurBundle:Items')->findOneById('6'); // ...
            $itemsPeau = $em->getRepository('CoeurBundle:Items')->findOneById('10'); // ...

            $itemsFleche = $em->getRepository('CoeurBundle:Items')->findOneById('9'); // ...



// On définit ici le poids TOTAL de l'utilisateur
            $poidsTotal = '';
            $itemsTotal = '';
            $itemsTotalQty = '';
            foreach ($ressources as $ressource) {
                $poidsTotal += $ressource->getItems()->getPoids() * $ressource->getQty();
                $itemsTotal += 1;
                $itemsTotalQty += $ressource->getQty();
            }

// Définition des variables global

            $StatsSoustraitFaim = 23.4;
            $StatsSoustraitSoif = 23.4;
            $StatsSoustraitFatigue = 23.4;

// On va définir tout de suite si l'utilisateur possède un Sac à dos ou bien rien du tout...
            if ($ressourcesSacADos) {
                $PoidsMaxi = 50;
            } else {
                $PoidsMaxi = 5;
            }


// Si les stats : Faim, Soif, Fatigue sont suppérieur à 25 l'utilisateurs peut chasser !
            if ($stats->getFaim() > ($StatsSoustraitFaim + 2) AND $stats->getSoif() > ($StatsSoustraitSoif + 2) AND $stats->getFatigue() > ($StatsSoustraitFatigue + 2)) {

                if ($poidsTotal < $PoidsMaxi) {


// On modifie les stats de l'utilisateurs !
                    $newFaim = $stats->getFaim() - $StatsSoustraitFaim;
                    $newSoif = $stats->getSoif() - $StatsSoustraitSoif;
                    $newFatigue = $stats->getFatigue() - $StatsSoustraitFatigue;

                    if ($newFaim <= 0) {
                        $newFaim = 0;
                    }
                    if ($newSoif <= 0) {
                        $newSoif = 0;
                    }
                    if ($newFatigue <= 0) {
                        $newFatigue = 0;
                    }

                    $stats->setFaim($newFaim);
                    $stats->setSoif($newSoif);
                    $stats->setFatigue($newFatigue);


                    if ($ressourcesArc) // Si l'utilisateur possède un Arc
                    {
                        if ($ressourcesFleche AND $ressourcesFleche->getQty() > 0) // Si l'utilisateur possède des flèches...
                        {
// On effecture un RAND pour obtenir les récoltes
                            $viande = mt_rand(0, $ressourcesFleche->getQty());
                            $oeuf = mt_rand(0, 3);
                            $champignon = mt_rand(0, 5);
                            $peau = mt_rand(0, $viande);

                            $useArc = TRUE;
                        } else { // Si il n'a pas de flèche, idem qu'à main nue
// On effecture un RAND pour obtenir les récoltes
                            $viande = mt_rand(0, 1);
                            $oeuf = mt_rand(0, 3);
                            $champignon = mt_rand(0, 20);
                            $peau = mt_rand(0, 1);

                            $useArc = FALSE;
                        }
                    } else // Pas d'arc pas de flèche, main nue !
                    {
// On effecture un RAND pour obtenir les récoltes
                        $viande = mt_rand(0, 1);
                        $oeuf = mt_rand(0, 3);
                        $champignon = mt_rand(0, 20);
                        $peau = mt_rand(0, 1);

                        $useArc = FALSE;
                    }


// On ajoute ou modifie les récoltes

// si le rand est suppérieur à 0
                    if ($viande > 0) {
                        if (!$ressourcesViande) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourceviande = new Ressources();

                            $newressourceviande->setUtilisateurss($user);
                            $newressourceviande->setItems($itemsViande);
                            $newressourceviande->setQty($viande);

                            $em->persist($newressourceviande);

                            if (TRUE === $useArc) // Si l'utilisateur a utiliser un arc, on supprime des quantités de flèche !
                            {
                                $newQtyFleche = $ressourcesFleche->getQty() - ($viande + 3);

                                if ($newQtyFleche <= 0) {
                                    $newQtyFleche = 0;
                                }

                                $ressourcesFleche->setQty($newQtyFleche);
                            }
                        } else // Sinon on le modifie
                        {
                            $newQty = $ressourcesViande->getQty() + $viande;
                            $ressourcesViande->setQty($newQty);

                            if (TRUE === $useArc) // Si l'utilisateur a utiliser un arc, on supprime des quantités de flèche !
                            {
                                $newQtyFleche = $ressourcesFleche->getQty() - ($viande + 3);

                                if ($newQtyFleche <= 0) {
                                    $newQtyFleche = 0;
                                }

                                $ressourcesFleche->setQty($newQtyFleche);
                            }

                        }
                    }

// si le rand est suppérieur à 0
                    if ($oeuf > 0) {
                        if (!$ressourcesOeuf) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourceoeuf = new Ressources();

                            $newressourceoeuf->setUtilisateurss($user);
                            $newressourceoeuf->setItems($itemsOeuf);
                            $newressourceoeuf->setQty($oeuf);

                            $em->persist($newressourceoeuf);

                        } else // Sinon on le modifie
                        {
                            $newQty = $ressourcesOeuf->getQty() + $oeuf;
                            $ressourcesOeuf->setQty($newQty);

                        }
                    }


                    if ($champignon > 0) // si le rand est suppérieur à 0
                    {
                        if (!$ressourcesChampignon) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourcechampignon = new Ressources();

                            $newressourcechampignon->setUtilisateurss($user);
                            $newressourcechampignon->setItems($itemsChampignon);
                            $newressourcechampignon->setQty($champignon);

                            $em->persist($newressourcechampignon);

                        } else // Sinon on le modifie
                        {
                            $newQty = $ressourcesChampignon->getQty() + $champignon;
                            $ressourcesChampignon->setQty($newQty);
                        }
                    }

                    if ($peau > 0) // si le rand est suppérieur à 0
                    {
                        if (!$ressourcesPeau) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourcepeau = new Ressources();

                            $newressourcepeau->setUtilisateurss($user);
                            $newressourcepeau->setItems($itemsPeau);
                            $newressourcepeau->setQty($peau);

                            $em->persist($newressourcepeau);

                        } else // Sinon on le modifie
                        {
                            $newQty = $ressourcesPeau->getQty() + $peau;
                            $ressourcesPeau->setQty($newQty);
                        }
                    }

                    $em->flush();


// C'est moche, mais c'est pour la gestion du pluriel singulier
                    if ($viande > 1) {
                        $plsv = 's';
                    } else {
                        $plsv = '';
                    }
                    if ($oeuf > 1) {
                        $plso = 's';
                    } else {
                        $plso = '';
                    }
                    if ($champignon > 1) {
                        $plsc = 's';
                    } else {
                        $plsc = '';
                    }
                    if ($peau > 1) {
                        $plsp = 'x';
                    } else {
                        $plsp = '';
                    }
                    if (!empty($newQtyFleche)) {
                        if ($newQtyFleche > 1) {
                            $plsf = 's';
                        } else {
                            $plsf = '';
                        }
                    } else {
                        $plsf = '';
                    }

                    $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On re récupère les Ressources pour être à jour !

// On affiche les récoltes
                    $recolte = $viande . ' viande' . $plsv . ', ' . $oeuf . ' oeuf' . $plso . ', ' . $champignon . ' champignon' . $plsc . ', ' . $peau . ' peau' . $plsp . ' récolté(s)';
                    if ($useArc) {
                        $nbFlecheUse = $viande + 3;
                        $recolte .= '<br />Vous avez utiliser un Arc et ' . $nbFlecheUse . ' flèche' . $plsf . '.';
                    }


                    return $this->render('CoeurBundle:Default:chasser.html.twig',
                        array(
                            'utilisateurs' => $user,
                            'stats' => $stats,
                            'ressources' => $ressources,
                            'poidsMaxi' => $PoidsMaxi,
                            'poidsTotal' => $poidsTotal,
                            'itemsTotal' => $itemsTotal,
                            'itemsTotalQty' => $itemsTotalQty,
                            'recolte' => $recolte
                        )
                    );
                } else {
                    $error = 'Vous n\'êtes pas en capacité de chasser, vous porter trop d\'objets !';
                    return $this->render('CoeurBundle:Default:chasser.html.twig',
                        array(
                            'utilisateurs' => $user,
                            'stats' => $stats,
                            'ressources' => $ressources,
                            'poidsMaxi' => $PoidsMaxi,
                            'poidsTotal' => $poidsTotal,
                            'itemsTotal' => $itemsTotal,
                            'itemsTotalQty' => $itemsTotalQty,
                            'error' => $error
                        )
                    );
                }
            } else // L'utilisateur n'est pas en capacité de chasser
            {
                $error = 'Vous n\'êtes pas en capacité de chasser !';
                return $this->render('CoeurBundle:Default:chasser.html.twig',
                    array(
                        'utilisateurs' => $user,
                        'stats' => $stats,
                        'ressources' => $ressources,
                        'poidsMaxi' => $PoidsMaxi,
                        'poidsTotal' => $poidsTotal,
                        'itemsTotal' => $itemsTotal,
                        'itemsTotalQty' => $itemsTotalQty,
                        'error' => $error
                    )
                );
            }
        }
    }
}