<?php

namespace App\Controller;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class QuestionController extends AbstractController
{

    /**
     * Display the homepage
     *
     * @Route("/", name="app_homepage")
     *
     * @return Response Render the homepage's template
     */
    public function homepage(): Response
    {
        return $this->render('question/homepage.html.twig');
    }

    /**
     * Display the question page
     *
     * @Route("/question/{slug}", name="app_question_show")
     * @param $slug question's slug
     * @param MarkdownParserInterface $parser Service to handle markdown
     * @param CacheInterface $cache Service to handle cache
     *
     * @return Response Render the question's template
     * @throws \Psr\Cache\InvalidArgumentException When cache arguments are not valid
     */
    public function show($slug, MarkdownParserInterface $parser, CacheInterface $cache): Response
    {
        $answers = [
            'I think you are `wrong` !',
            'Absolutely, you are right !',
            'IMO IDK !',
        ];

        $questionText = "I've been turned into a cat, any *thoughts* on how to turn back? 
            While I'm **adorable**, I don't really care for cat food.";

        $parsedQuestionText = $cache->get('markdown_'.md5($questionText), function () use($parser, $questionText){
            return $parser->transformMarkdown($questionText);
        });

        dump($cache);

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'answers' => $answers,
            'questionText' => $parsedQuestionText,
        ]);
    }
}