<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="test")
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
     * @Route("/{{ request.getLocale() }}/", name="accueil")
     */
    public function acceuil(Request $request): Response
    {
        $lang = $request->getLocale();
        if ($lang == 'fr'){
            return $this->render('site/fr/index.html.twig',[
                'controller_name'=>'test'
            ]);
        }
        if ($lang == 'en'){
            return $this->render('site/fr/index.html.twig',[
                'controller_name'=>$lang
            ]);
        }
        else{
            return $this->render('site/langnotfound.html.twig',[
                'controller_name'=>$lang
            ]);
        }
    }
	/**
     * @Route("/{_locale}/portfolio", name="portfolio")
     */
	public function portfolio(Request $request): Response
    {
        $lang = $request->getLocale();
        if ($lang == 'fr'){
            return $this->render('site/fr/portfolio.html.twig');
        }
    }
	/**
     * @Route("/{_locale}/cv", name="cv")
     */
	public function cv(Request $request): Response
    {
        $lang = $request->getLocale();
        if ($lang == 'fr'){
            return $this->render('site/cv.html.twig');
        }
    }
	/**
     * @Route("/{_locale}/blog", name="blog")
     */
	public function blog(Request $request): Response
    {
        $lang = $request->getLocale();
        if ($lang == 'fr'){
            return $this->render('site/blog.html.twig');
        }
    }
}
