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
     * @Route("/ajout_commande")
     */
    public function ajout_commande(Security $security): Response
    {
        $user = $security->getUser();

        return $this->redirectToRoute("panier", [
            'user'=>$user->getUsername(),
        ]);
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
}
