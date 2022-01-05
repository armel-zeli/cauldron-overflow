<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/{page<\d+>}", name="app_homepage")
     *
     * @param QuestionRepository $repository
     * @param int $page
     * @return Response Render the homepage's template
     */

    public function homepage(QuestionRepository $repository, int $page = 1): Response
    {
        $queryBuilder = $repository->createAskedOrderByNewestQueryBuilder();

        $pagerFanta = new Pagerfanta(
            new QueryAdapter($queryBuilder)
        );

        $pagerFanta->setMaxPerPage(5);

        $pagerFanta->setCurrentPage($page);

        return $this->render(
            'question/homepage.html.twig',
            ['pager' => $pagerFanta]
        );
    }

    /**
     * @Route("/question/new")
     * @IsGranted("ROLE_USER")
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

        return $this->render('question/show.html.twig', [
            'question' => $question,
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

    /**
     * @Route("/questions/edit/{slug}", name="app_question_edit")
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question)
    {
        $this->denyAccessUnlessGranted('EDIT', $question);

        return $this->render('question/edit.html.twig', [
            'question' => $question,
        ]);
    }
}