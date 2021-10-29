<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param QuestionRepository $repository
     * @return Response Render the homepage's template
     */

    public function homepage(QuestionRepository $repository): Response
    {
        $questions = $repository->findAllAskedOrderByNewest();

        return $this->render(
            'question/homepage.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/question/new")
     */
    public function new()
    {
        return new Response('New functionality soon');
    }

    /**
     * Display the question show page
     *
     * @Route("/question/{slug}", name="app_question_show")
     * @param Question $question question's slug
     *
     * @return Response Render the question's template
     */

    public function show(Question $question): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode !');
        }

        $answers = [
            'I think you are `wrong` !',
            'Absolutely, you are right !',
            'IMO IDK !',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("question/{slug}/vote", name="app_question_vote", methods={"POST"})
     *
     * @param Question $question
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function questionVote(
        Question $question,
        Request $request,
        EntityManagerInterface $em
    ): RedirectResponse {
        $direction = $request->request->get('direction');
        if ($direction === 'up') {
            $question->upVote();
        } elseif ($direction === 'down') {
            $question->downVote();
        }

        $em->flush();

        return $this->redirectToRoute('app_question_show', ['slug' => $question->getSlug()]);
    }
}