<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;

class MinerController extends Controller
{
    public function minerAction()
    {
// L'utilisateur est bien en ROLE_USER
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
// On récupère l'utilisateur loggé
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources

            $ressourcesBois = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 1, 'utilisateurss' => $user->getId())); // On récupère l'item "1" dans les ressources
            $ressourcesPierre = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 2, 'utilisateurss' => $user->getId())); // On récupère l'item "2" dans les ressources

            // On récupère la liste des items pour modifier les rand().
            $ressourcesPioche = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 5, 'utilisateurss' => $user->getId())); // ...
            $ressourcesHache = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 7, 'utilisateurss' => $user->getId())); // ...
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // ...


            $itemsBois = $em->getRepository('CoeurBundle:Items')->findOneById('1'); // On récupère les infos de l'item "bois"
            $itemsPierre = $em->getRepository('CoeurBundle:Items')->findOneById('2'); // On récupère les infos de l'item "pierre"
            $itemsPioche = $em->getRepository('CoeurBundle:Items')->findOneById('5'); // On récupère les infos de l'item "pioche"
            $itemsHache = $em->getRepository('CoeurBundle:Items')->findOneById('7'); // On récupère les infos de l'item "hache"

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

            $StatsSoustraitFaim = 12.7;
            $StatsSoustraitSoif = 4.9;
            $StatsSoustraitFatigue = 20.1;

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



                    if ($ressourcesPioche AND !$ressourcesHache) // Si l'utilisateur possède une Pioche mais pas de Hache
                    {
// On effecture un RAND pour obtenir les récoltes
                        $bois = mt_rand(0, 5);
                        $pierre = mt_rand(0, 30);

                        $usePioche = TRUE;
                        $useHache = FALSE;
                    } elseif (!$ressourcesPioche AND $ressourcesHache) // Si il n'a pas de Pioche mais qu'une Hache, idem qu'à main nue
                    {
// On effecture un RAND pour obtenir les récoltes
                        $bois = mt_rand(0, 30);
                        $pierre = mt_rand(0, 5);

                        $usePioche = FALSE;
                        $useHache = TRUE;
                    } elseif ($ressourcesPioche AND $ressourcesHache) // Sinon l'utilisateurs possède les deux
                    {
// On effecture un RAND pour obtenir les récoltes
                        $bois = mt_rand(0, 30);
                        $pierre = mt_rand(0, 30);

                        $usePioche = TRUE;
                        $useHache = TRUE;
                    } else
                    {
// On effecture un RAND pour obtenir les récoltes
                        $bois = mt_rand(0, 5);
                        $pierre = mt_rand(0, 5);

                        $usePioche = FALSE;
                        $useHache = FALSE;
                    }


// On ajoute ou modifie les récoltes

// si le rand est suppérieur à 0
                    if ($bois > 0) {
                        if (!$ressourcesBois) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourcesBois = new Ressources();

                            $newressourcesBois->setUtilisateurss($user);
                            $newressourcesBois->setItems($itemsBois);
                            $newressourcesBois->setQty($bois);

                            $em->persist($newressourcesBois);
                        }
                        else
                        {
                            $newQty = $ressourcesBois->getQty() + $bois;
                            $ressourcesBois->setQty($newQty);
                        }
                    }

// si le rand est suppérieur à 0
                    if ($pierre > 0) {
                        if (!$ressourcesPierre) // Si l'utilisateur n'a pas encore cet item dans l'inventaire
                        {
                            $newressourcesPierre = new Ressources();

                            $newressourcesPierre->setUtilisateurss($user);
                            $newressourcesPierre->setItems($itemsPierre);
                            $newressourcesPierre->setQty($pierre);

                            $em->persist($newressourcesPierre);
                        }
                        else
                        {
                            $newQty = $ressourcesPierre->getQty() + $pierre;
                            $ressourcesPierre->setQty($newQty);
                        }
                    }

                    $em->flush();


// C'est moche, mais c'est pour la gestion du pluriel singulier
                    if ($pierre > 1) {
                        $plsp = 's';
                    } else {
                        $plsp = '';
                    }

                    $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On re récupère les Ressources pour être à jour !

// On affiche les récoltes
                    $recolte = $bois . ' bois, ' . $pierre . ' pierre' . $plsp . ' récolté(s)';
                    if ($useHache AND !$usePioche) {
                        $recolte .= '<br />Vous avez utiliser une Hache';
                    } elseif ($usePioche AND !$useHache) {
                        $recolte .= '<br />Vous avez utiliser une Pioche';
                    }
                    elseif ($usePioche AND $useHache) {
                        $recolte .= '<br />Vous avez utiliser une Hache et une Pioche';
                    }
                    else {
                        $recolte .= '<br />Vous n\'avez pas de Pioche et d\'Hache, vos ressources sont donc minimiser, pensez à craft une Hache ou une Pioche';
                    }

                    return $this->render('CoeurBundle:Default:miner.html.twig',
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
                    $error = 'Vous n\'êtes pas en capacité de miner, vous porter trop d\'objets !';
                    return $this->render('CoeurBundle:Default:miner.html.twig',
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
            } else // L'utilisateur n'est pas en capacité de miner
            {
                $error = 'Vous n\'êtes pas en capacité de miner !';
                return $this->render('CoeurBundle:Default:miner.html.twig',
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