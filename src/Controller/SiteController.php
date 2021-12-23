<?php

namespace App\Controller;

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
     * @Route("/{_locale<%app.supported_locales%>}/", name="accueil")
     */
    public function acceuil(): Response
    {
        return $this->render('site/index.html.twig',[
            'controller_name'=>'test'
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
	public function cv(): Response
    {
        return $this->render('site/cv.html.twig');
    }
	/**
     * @Route("/{_locale<%app.supported_locales%>}/blog", name="blog")
     */
	public function blog(): Response
    {
        return $this->render('site/blog.html.twig');
    }
}
