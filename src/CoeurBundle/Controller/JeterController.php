<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;

class JeterController extends Controller
{
    public function deleteAction($item)
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

            $ressourcesExist = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => $item, 'utilisateurss' => $user->getId())); // On récupère l'item à jeter

// Ici on vérifie que l'utilisateurs possède bien l'item
            if ($ressourcesExist) {
                if ($ressourcesExist->getQty() >= 0) // Si il a bien l'item, on vérifie quand même qu'il a une quantité suppérieur ou égal à 0...
                {
                    $newQtyRessource = $ressourcesExist->getQty() - 1; // On décrémente la quantité de l'item
                    $ressourcesExist->setQty($newQtyRessource);

                    $em->flush();

                    $ressourcesView = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => $item, 'utilisateurss' => $user->getId())); // On re récupère l'item à utiliser
                    if ($ressourcesView AND $ressourcesView->getQty() < 1) // Si l'item est passé à 0 on supprime.
                    {
                        $em->remove($ressourcesView);
                        $em->flush();
                    }

                    $MessageRessource = 'Vous venez de jeter la ressource ' . $ressourcesExist->getItems()->getNom();
                } else { // Si l'utilisateur n'a pas assez de quantité pour l'item
                    $MessageRessource = 'Vous n\'avez pas assez de quantité pour supprimer cette ressource !';
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

            return $this->render('CoeurBundle:Default:jeter.html.twig',
                array(
                    'utilisateurs' => $user,
                    'stats' => $stats,
                    'ressources' => $ressources,
                    'poidsMaxi' => $PoidsMaxi,
                    'itemsTotal' => $itemsTotal,
                    'poidsTotal' => $poidsTotal,
                    'itemsTotalQty' => $itemsTotalQty,
                    'messageDelete' => $MessageRessource
                )
            );
        }
    }
}