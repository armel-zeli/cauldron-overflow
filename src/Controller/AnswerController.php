<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnswerController extends BaseController
{
    /**
     * Api to handle the answer vote count
     *
     * @Route("/answers/{id}/vote/{direction<up|down>}", methods="POST")
     *
     * @param Answer $answer The answer
     * @param string $direction The type of vote up or down
     * @param EntityManagerInterface $entityManager The entity manager interface
     * @param LoggerInterface $logger The Logger interface
     *
     * @return JsonResponse Return the number of votes
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     */

    public function answerVote(Answer $answer, string $direction, EntityManagerInterface $entityManager, LoggerInterface $logger): JsonResponse
    {
        $logger->info('{user} is voting in {answer}!', [
            'answer' => $answer->getId(),
            'user'=> $this->getUser()->getEmail()
        ]);
        if ($direction === 'up') {
            $answer->upVote();
        } else {
            $answer->downVote();
        }

        $entityManager->flush();

        return $this->json(['votes' => $answer->getVotesString()]);
    }

    /**
     * Return the most popular questions
     *
     * @Route("/answers/popular", name="app_popular_answers")
     *
     * @param AnswerRepository $repository
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\Query\QueryException
     */

    public function popularAnswers(AnswerRepository $repository, Request $request) : Response
    {
        $popularAnswers = $repository->findMostPopular($request->query->get('q'));

        return $this->render(
            'answers/popularAnswers.html.twig',
            ['answers' => $popularAnswers]
        );
    }


}