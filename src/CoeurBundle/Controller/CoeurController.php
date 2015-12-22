<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;

class CoeurController extends Controller
{
    public function indexAction()
    {

        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // On récupère l'item "11" dans les ressources

            // On définit ici le poids TOTAL de l'utilisateur
            $poidsTotal = '';
            $itemsTotal = '';
            $itemsTotalQty = '';
            foreach ($ressources as $ressource)
            {
                $poidsTotal += $ressource->getItems()->getPoids() * $ressource->getQty();
                $itemsTotal += 1;
                $itemsTotalQty += $ressource->getQty();
            }

            // On va définir tout de suite si l'utilisateur possède un Sac à dos ou bien rien du tout...
            if ($ressourcesSacADos) {
                $PoidsMaxi = 50;
            }
            else {
                $PoidsMaxi = 5;
            }

            return $this->render('CoeurBundle:Default:index.html.twig',
                array(
                    'utilisateurs' => $user,
                    'stats' => $stats,
                    'ressources' => $ressources,
                    'poidsMaxi' => $PoidsMaxi,
                    'itemsTotal' => $itemsTotal,
                    'itemsTotalQty' => $itemsTotalQty,
                    'poidsTotal' => $poidsTotal
                )
            );
        }
    }

    public function boireAction()
    {

    }
}
