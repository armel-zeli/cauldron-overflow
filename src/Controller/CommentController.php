<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * Api to handle the comment vote count
     *
     * @Route("/comments/{id}/vote/{direction<up|down>}", methods="POST")
     *
     * @param int $id The comment id
     * @param string $direction The type of vote up or down
     * @param LoggerInterface $logger Service to log informations
     *
     * @return JsonResponse Return the number of votes
     */
    public function commentVote(int $id, string $direction, LoggerInterface $logger) : JsonResponse
    {
        // todo use $id to query database

        if($direction === 'up'){
            $logger->info('Voting up!');
            $currentVoteCount = rand(7,100);
        } else {
            $logger->info('Voting down!');
            $currentVoteCount = rand(0,5);
        }

        return $this->json(['votes'=>$currentVoteCount]);
    }
}