<?php

namespace App\Controller;

use App\Entity\Profile;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/download", name="downloadNoLocale")
     */
    public function downloadNoLocale(): Response
    {
        return $this->redirectToRoute("download");
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
        new BinaryFileResponse("../../public/dl/cv.pdf");
    }
}
