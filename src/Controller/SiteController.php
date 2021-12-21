<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     *Route("/", name: "Accueil")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
	/**
     * @Route("/portfolio", name="Portfolio")
     */
	public function portfolio()
    {
        return $this->render('site/portfolio.html.twig');
    }
	/**
     * @Route("/cv", name="cv")
     */
	public function cv()
    {
        return $this->render('site/cv.html.twig');
    }
	/**
     * @Route("/blog", name="blog")
     */
	public function blog()
    {
        return $this->render('site/blog.html.twig');
    }
}
