<?php

namespace CoeurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CoeurBundle\Entity\Stats;
use CoeurBundle\Entity\Ressources;
use CoeurBundle\Entity\Items;
use CoeurBundle\Entity\Craft;

class CraftController extends Controller
{
    public function craftAction()
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources
            $items = $em->getRepository('CoeurBundle:Items')->findAll(); // On récupère les besoins de l'item
            $craft = $em->getRepository('CoeurBundle:Craft')->findByItems($items); // On récupère les besoins de l'item
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // On récupère l'item "11" dans les ressources

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

            foreach($craft as $itemsAll)
            {
                echo '<pre>';
                \Doctrine\Common\Util\Debug::dump($itemsAll);
                echo '</pre>';
            }


            return $this->render('CoeurBundle:Default:craft.html.twig',
                array(
                    'utilisateurs' => $user,
                    'stats' => $stats,
                    'ressources' => $ressources,
                    'poidsMaxi' => $PoidsMaxi,
                    'itemsTotal' => $itemsTotal,
                    'itemsTotalQty' => $itemsTotalQty,
                    'itemsAll' => $craft,
                    'poidsTotal' => $poidsTotal
                )
            );
        }
    }

    public function craftitemAction($item)
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            if (!$item) {
                throw $this->createNotFoundException('Aucun item trouvé.');
            }

            $em = $this->getDoctrine()->getManager();
            $stats = $em->getRepository('CoeurBundle:Stats')->findOneByUtilisateurs($user); // On récupère les stats
            $ressources = $em->getRepository('CoeurBundle:Ressources')->getAllItems($user); // On récupère les Ressources
            $items = $em->getRepository('CoeurBundle:Items')->findOneById($item); // On récupère les items
            $ressourcesSacADos = $em->getRepository('CoeurBundle:Ressources')->findOneBy(array('items' => 11, 'utilisateurss' => $user->getId())); // On récupère l'item "11" dans les ressources

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


            return $this->render('CoeurBundle:Default:craft.html.twig',
                array(
                    'utilisateurs' => $user,
                    'stats' => $stats,
                    'ressources' => $ressources,
                    'poidsMaxi' => $PoidsMaxi,
                    'itemsTotal' => $itemsTotal,
                    'itemsTotalQty' => $itemsTotalQty,
                    'itemsAll' => $items,
                    'itemHTML' => $itemHTML,
                    'poidsTotal' => $poidsTotal
                )
            );
        }
    }
}