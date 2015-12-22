<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;

class UtiliserController extends Controller
{
    public function utiliserAction($item)
    {

        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            if (!$item) {
                throw $this->createNotFoundException('Aucun item trouvé.');
            }

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // On récupère l'item "11" dans les ressources

            $ressourcesExist = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => $item, 'utilisateurss' => $user->getId())); // On récupère l'item à utiliser

// Ici on vérifie que l'utilisateurs possède bien l'item
            if ($ressourcesExist) {
                if ($ressourcesExist->getQty() > 0) // Si il a bien l'item, on vérifie quand même qu'il a une quantité suppérieur à 0...
                {
                    if ($ressourcesExist->getItems()->getStats() != "0,0,0,0,0") {
                        $newQtyRessource = $ressourcesExist->getQty() - 1; // On décrémente la quantité de l'item
                        $ressourcesExist->setQty($newQtyRessource);

// On déclare les stats de l'item utiliser
                        $statsItem = explode(',', $ressourcesExist->getItems()->getStats()); // On sépare les stats de l'item par des virgules

                        $newVieStats = $stats->getVie() + $statsItem[0];
                        $newFaimStats = $stats->getFaim() + $statsItem[1];
                        $newSoifStats = $stats->getSoif() + $statsItem[2];
                        $newFatigueStats = $stats->getFatigue() + $statsItem[3];
                        $newTemperatureStats = $stats->getTemperature() + $statsItem[4];

// Si la stat est déjà à 100% on ne va pas ajouter de stat
                        if ($newVieStats > 100) {
                            $newVieStats = 100;
                        }
                        if ($newFaimStats > 100) {
                            $newFaimStats = 100;
                        }
                        if ($newSoifStats > 100) {
                            $newSoifStats = 100;
                        }
                        if ($newFatigueStats > 100) {
                            $newFatigueStats = 100;
                        }
                        if ($newTemperatureStats > 100) {
                            $newTemperatureStats = 100;
                        }

// On met à jour les stats
                        $stats->setVie($newVieStats);
                        $stats->setFaim($newFaimStats);
                        $stats->setSoif($newSoifStats);
                        $stats->setFatigue($newFatigueStats);
                        $stats->setTemperature($newTemperatureStats);


                        $em->flush();

                        $ressourcesView = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => $item, 'utilisateurss' => $user->getId())); // On re récupère l'item à utiliser
                        if ($ressourcesView AND $ressourcesView->getQty() < 1) // Si l'item est passé à 0 on supprime.
                        {
                            $em->remove($ressourcesView);
                            $em->flush();
                        }
                        $MessageRessource = 'Vous venez d\'utiliser la ressource <u>' . $ressourcesExist->getItems()->getNom() . '</u>';
                    } else {
                        $MessageRessource = 'Vous ne pouvez pas utiliser la ressource <u>' . $ressourcesExist->getItems()->getNom() . '</u>';
                    }
                } else { // Si l'utilisateur n'a pas assez de quantité pour l'item
                    $MessageRessource = 'Vous n\'avez pas assez de quantité pour la ressource <u>' . $ressourcesExist->getItems()->getNom() . '</u>';
                }
            } else { // L'utilisateur essaie de nous piéger car il ne possède pas cet item !
                $MessageRessource = 'Vous ne posséder pas cette ressource ou n\'existe pas !';
            }

// On définit ici le poids TOTAL de l'utilisateur
            $poidsTotal = '';
            $itemsTotal = '';
            $itemsTotalQty = '';
            foreach ($ressources as $ressource) {
                $poidsTotal += $ressource->getItems()->getPoids() * $ressource->getQty();
                $itemsTotal += 1;
                $itemsTotalQty += $ressource->getQty();
            }

// On va définir tout de suite si l'utilisateur possède un Sac à dos ou bien rien du tout...
            if ($ressourcesSacADos) {
                $PoidsMaxi = 50;
            } else {
                $PoidsMaxi = 5;
            }

            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On re récupère les Ressources

            return $this->render('CoeurBundle:Default:utiliser.html.twig',
                array(
                    'utilisateurs' => $user,
                    'stats' => $stats,
                    'ressources' => $ressources,
                    'poidsMaxi' => $PoidsMaxi,
                    'itemsTotal' => $itemsTotal,
                    'poidsTotal' => $poidsTotal,
                    'itemsTotalQty' => $itemsTotalQty,
                    'messageUse' => $MessageRessource
                )
            );
        }
    }
}