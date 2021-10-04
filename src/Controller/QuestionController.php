<?php

namespace App\Controller;

use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
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
     * @Route("/question/new")
     */
    public function new(EntityManagerInterface $manager)
    {
        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-'.rand(0, 1000))
            ->setQuestion(
                <<<EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
Does anyone have a spell to call your pants back?
EOF
            );
        if(rand(1,10) > 2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        $manager->persist($question);
        $manager->flush();

        return new Response(sprintf(
            'New question with id #%d and slug %s added',
            $question->getId(),
            $question->getSlug()
        ));
    }

    /**
     * Display the question show page
     *
     * @Route("/question/{slug}", name="app_question_show")
     * @param $slug question's slug
     * @param MarkdownHelper $markdownHelper Service to handle markdown
     * @param EntityManagerInterface $manager
     *
     * @throws \Psr\Cache\InvalidArgumentException If arguments of are invalid
     * @return Response Render the question's template
     */

    public function show($slug, MarkdownHelper $markdownHelper, EntityManagerInterface $manager): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode !');
        }

        $repository = $manager->getRepository(Question::class);

        /** @var Question|null $question */
        $question = $repository->findOneBy(['slug' => $slug]);

        if(!$question){
           throw $this->createNotFoundException(sprintf('No question found for slug "%s"', $slug));
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
}