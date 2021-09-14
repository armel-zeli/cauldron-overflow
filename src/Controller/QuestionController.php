<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        return new Response('<h1>Hello world</h1>');
    }

    /**
     * @Route("/questions/{slug}")
     */
    public function show($slug): Response
    {
        $answers = [
            'I think you are wrong !',
            'Absolutely, you are right !',
            'IMO IDK !'
        ];

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'answers' => $answers,
        ]);
    }
}