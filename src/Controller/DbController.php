<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Prestation;
use App\Entity\Panier;  

class DbController extends AbstractController
{
    /**
     * @Route("/ajout_prestation")
     */
    public function ajout_prestation(Request $request, EntityManagerInterface $manager): Response
    {
        $prestation = new Prestation();
        $nom = $request->request->get("Nom");
        $desc = $request->request->get("Description");
        $image = $request->request->get("Image");
        $prix = $request->request->get("Prix");
        $prestation->setNom($nom);
        $prestation->setDescription($desc);
        if($image){
            $prestation->setImage($image);
        }
        $prestation->setPrix($prix);
        $manager->persist($prestation);
		$manager->flush();
        return $this->redirectToRoute("gestionprestations");
    }
    
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/default_remplissagedb")
     */
    public function default_remplissagedb(EntityManagerInterface $manager): Response
    {
        $presta1 = new Prestation();
        $presta1->setNom("Dev web");
        $presta1->setDescription("Devellopement Web");
        $presta1->setPrix(8000.0);
        $manager->persist($presta1);
        $presta2 = new Prestation();
        $presta2->setNom("Réalisation architecture réseau");
        $presta2->setDescription("Concéption et réalisation de réseaux avec mise en place de systèmes de sécurité");
        $presta2->setPrix(5000.0);
        $manager->persist($presta2);
        $presta3 = new Prestation();
        $presta3->setNom("Intégration automatisme industrielle");
        $presta3->setDescription("Concéption et réalisation de systèmes automatisés industriel.");
        $presta3->setPrix(14000.0);
        $manager->persist($presta3);
        $manager->flush();
        return $this->redirectToRoute("gestionprestations");
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/suppr_prestation")
     */
    public function suppr_prestation(Request $request, EntityManagerInterface $manager): Response
    {
        $id = $request->request->get("presta_Id");
        $prestation=$manager->getRepository(Prestation::class)->find($id);
        $paniers = $prestation->getPaniers();
        foreach($paniers as $panier){
            $panier->setTotal($panier->getTotal()-$prestation->getPrix());
        }
        $manager->remove($prestation);
		$manager->flush();
        return $this->redirectToRoute("gestionprestations");
    }
    
    /**
     * @Route("/suppr_panier")
     */
    public function suppr_panier(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $panierid = $request->request->get("panier");
        $panier = $manager->getRepository(Panier::class)->find($panierid);
        $manager->remove($panier);
		$manager->flush();
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/admin_panier")
     */
    public function admin_panier(Request $request, EntityManagerInterface $manager): Response
    {
        $etatspaniers = $request->request->get("etats");
        foreach($etatspaniers as $panierid=>$etatpanier){
            if($etatpanier){
                $panier=$manager->getRepository(Panier::class)->find($panierid);
                $panier->setEtat($etatpanier);
            }
        }
        $paniersid = $request->request->get("paniersuppr");
        if($paniersid){
            foreach ($paniersid as $panierid){
                $panier = $manager->getRepository(Panier::class)->find($panierid);
                $manager->remove($panier);
            }
        }
        $manager->flush();
        return $this->redirectToRoute("gestioncommandes");
    }

    /**
     * @Route("/suppr_commande")
     */
    public function suppr_commande(Request $request, EntityManagerInterface $manager): Response
    {
        $prestations= $request->request->get("presta");
        if($prestations){
            $panierid = $request->request->get("panierid");
            $panier = $manager->getRepository(Panier::class)->find($panierid);
            foreach ($prestations as $id){
                $prestation=$manager->getRepository(Prestation::class)->find($id);
                $panier->setTotal($panier->getTotal()-$prestation->getPrix());
                $panier->removeCommande($prestation);
            }
            $manager->flush();
        }
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/ajout_panier")
     */
    public function ajout_panier(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $prestations = $request->request->get("presta");
        if ($prestations){
            $panier = new Panier();
            $user = $security->getUser();
            $panier->setUserid($user);
            $total = 0;
            foreach ($prestations as $id){
                $prestation= $manager->getRepository(Prestation::class)->find($id);
                $panier->addCommande($prestation);
                $total = $total+$prestation->getPrix();
            }
            $panier->setTotal($total);
            $manager->persist($panier);
            $manager->flush();
        }
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/valider_panier")
     */
    public function valider_panier(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $panierid = $request->request->get("panier");
        $panier = $manager->getRepository(Panier::class)->find($panierid);
        $panier->setEtat("ATTENTE");
        $manager->flush();
        return $this->redirectToRoute("panier");
    }
}
