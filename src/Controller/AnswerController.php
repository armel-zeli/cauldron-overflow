<?php

namespace App\Controller;

use App\Entity\Answer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}