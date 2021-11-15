<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * Api to handle the answer vote count
     *
     * @Route("/answers/{id}/vote/{direction<up|down>}", methods="POST")
     *
     * @param Answer $answer The answer
     * @param string $direction The type of vote up or down
     * @param EntityManagerInterface $entityManager The entity manager interface
     *
     * @return JsonResponse Return the number of votes
     */

    public function answerVote(Answer $answer, string $direction, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($direction === 'up') {
            $answer->upVote();
        } else {
            $answer->downVote();
        }

        $entityManager->flush();

        return $this->json(['votes' => $answer->getVotes()]);
    }

    /**
     * @Route("/answers/popular", name="app_popular_answers")
     *
     * @param AnswerRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function popularAnswers(AnswerRepository $repository) : Response
    {
        $popularAnswers = $repository->findMostPopular();

        return $this->render(
            'answers/popularAnswers.html.twig',
            ['answers' => $popularAnswers]
        );
    }


}