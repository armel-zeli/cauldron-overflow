<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

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
     * Display the question show page
     *
     * @Route("/question/{slug}", name="app_question_show")
     * @param $slug question's slug
     * @param MarkdownHelper $markdownHelper Service to handle markdown
     *
     * @throws \Psr\Cache\InvalidArgumentException If arguments of are invalid
     * @return Response Render the question's template
     */

    public function show($slug, MarkdownHelper $markdownHelper): Response
    {
        if($this->isDebug){
            $this->logger->info('We are in debug mode !');
        }
        $answers = [
            'I think you are `wrong` !',
            'Absolutely, you are right !',
            'IMO IDK !',
        ];

        $questionText = "I've been turned into a cat, any *thoughts* on how to turn back? 
            While I'm **adorable**, I don't really care for **cat** food.";

        $parsedQuestionText = $markdownHelper->parse($questionText);

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'answers' => $answers,
            'questionText' => $parsedQuestionText,
        ]);
    }
}