<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Profile;
use App\Entity\Prestation;
use App\Entity\Panier;  

class SiteController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function accueilNoLocale(): Response
    {
        return $this->redirectToRoute("accueil");
    }
    /**
     * @Route("/portfolio")
     */
    public function portfolioNoLocale(): Response
    {
        return $this->redirectToRoute("portfolio");
    }
    /**
     * @Route("/cv")
     */
    public function cvoNoLocale(): Response
    {
        return $this->redirectToRoute("cv");
    }
    /**
     * @Route("/blog")
     */
    public function blogoNoLocale(): Response
    {
        return $this->redirectToRoute("blog");
    }
    /**
     * @Route("/projets")
     */
    public function projetsNoLocale(): Response
    {
        return $this->redirectToRoute("projets");
    }
    /**
     * @Route("/apropos")
     */
    public function aproposNoLocale(): Response
    {
        return $this->redirectToRoute("apropos");
    }
    /**
     * @Route("/download", name="downloadNoLocale")
     */
    public function downloadNoLocale(): Response
    {
        return $this->redirectToRoute("download");
    }
    /**
     * @Route("/prestations")
     */
    public function prestationsNoLocale(): Response
    {
        return $this->redirectToRoute("prestations");
    }

    /**
     * @Route("/gestionprestations")
     */
    public function gestionprestationsNoLocale(): Response
    {
        return $this->redirectToRoute("gestionprestations");
    }
    /**
     * @Route("/panier")
     */
    public function panierNoLocale(): Response
    {
        return $this->redirectToRoute("panier");
    }
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
        $prestation->setImage($image);
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
        $presta1->setPrix(100.0);
        $manager->persist($presta1);
        $presta2 = new Prestation();
        $presta2->setNom("Réalisation architecture réseau");
        $presta2->setDescription("Concéption et réalisation de réseaux avec mise en place de systèmes de sécurité");
        $presta2->setPrix(1000.0);
        $manager->persist($presta2);
        $presta3 = new Prestation();
        $presta3->setNom("Intégration automatisme industrielle");
        $presta3->setDescription("Concéption et réalisation de systèmes automatisés industriel.");
        $presta3->setPrix(8000.0);
        $manager->persist($presta3);
        $manager->flush();
        return $this->redirectToRoute("gestionprestations");
    }

    /**
     * @Route("/suppr_prestation")
     */
    public function suppr_prestation(Request $request, EntityManagerInterface $manager): Response
    {
        $id = $request->request->get("presta_Id");
        $prestation=$manager->getRepository(Prestation::class)->find($id);
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
        $user = $security->getUser();
        $user->removePanier($panier);
		$manager->flush();
        return $this->redirectToRoute("panier");
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
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/valider_panier")
     */
    public function valider_panier(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $panierid = $request->request->get("panier");
        $panier = $manager->getRepository(Panier::class)->find($panierid);
        $panier->setEtat("Attente");
        $manager->flush();
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/", name="accueil")
     */
    public function acceuil(): Response
    {
        return $this->render('site/index.html.twig',[
        ]);
    }
    /**
     * @Route("/{_locale<%app.supported_locales%>}/apropos", name="apropos")
     */
	public function apropos(): Response
    {
        return $this->render('site/apropos.html.twig');
    }
	/**
     * @Route("/{_locale<%app.supported_locales%>}/portfolio", name="portfolio")
     */
	public function portfolio(): Response
    {
        return $this->render('site/portfolio.html.twig');
    }
	/**
     * @Route("/{_locale<%app.supported_locales%>}/cv", name="cv")
     */
	public function cv(Request $request): Response
    {
        $profile = new Profile();
        $form = $this->createFormBuilder($profile)
            ->add('prenom', TextType::class,['label' => 'Prenom:'])
            ->add('nom', TextType::class,['label' => 'Nom:'])
            ->add('email', TextType::class,['label' => 'E-mail:'])
            ->add('save', SubmitType::class, ['label' => 'télecharger CV'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid())
        {
            return $this->redirectToRoute("downloadNoLocale");
        }
        return $this->renderForm('site/cv.html.twig',[
            'form' => $form,
        ]);
    }
	/**
     * @Route("/{_locale<%app.supported_locales%>}/blog", name="blog")
     */
	public function blog(): Response
    {
        return $this->render('site/blog.html.twig');
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/download", name="download")
     */
    public function download(): Response
    {
        $response = new BinaryFileResponse("dl/cv.pdf");
        return $response;
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/projets", name="projets")
     */
    public function projets(): Response
    {
        return $this->render('site/projets.html.twig');
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{_locale<%app.supported_locales%>}/gestionprestations", name="gestionprestations")
     */
    public function gestionprestations (EntityManagerInterface $manager): Response
    {
        $prestations=$manager->getRepository(Prestation::class)->findAll();
        return $this->render('site/gestionprestations.html.twig', [
            'prestations'=>$prestations,
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/prestations", name="prestations")
     */
    public function prestations (EntityManagerInterface $manager): Response
    {
        $prestations=$manager->getRepository(Prestation::class)->findAll();
        return $this->render('site/prestations.html.twig', [
            'prestations'=>$prestations,
        ]);
    }
    /**
     *  @Route("/{_locale<%app.supported_locales%>}/panier", name="panier")
     */
    public function panier(EntityManagerInterface $manager, Security $security): Response
    {
        $user = $security->getUser();
        $temppaniers = $user->getPaniers();
        $paniers = [];
        $commandes = [];
        foreach($temppaniers as $panier){
            if (!$panier->getEtat()){
                array_push($paniers, $panier);
            }
            if ($panier->getEtat()){
                array_push($commandes, $panier);
            }
        }
        dump($commandes);
        return $this->render('site/panier.html.twig', [
            'paniers'=>$paniers,
            'commandes'=>$commandes,
        ]);
    }

    /**
     * @Route("/{_locale<%app.supported_locales%>}/gestioncommandes, name="gestioncommandes"")
     */
    public function gestioncommandes(EntityManagerInterface $manager, Security $security): Response
    {
        
    }
     
}
