<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController
{
    /**
     * @Route("/")
     */
    public function homepage(): Response
    {
        return new Response('<h1>Hello world</h1>');
    }

    /**
     * @Route("/questions/{slug}", requirements={})
     */
    public function show($slug): Response
    {
        return new Response(
            sprintf(
                'Futur page to show question "%s"!',
                $slug
            )
        );
    }
}