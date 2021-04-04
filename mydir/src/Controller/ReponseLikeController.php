<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\ReponseLike;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReponseLikeController extends AbstractController
{
    /**
     * @Route("/reponse/like", name="reponse_like")
     */
    public function index(): Response
    {
        return $this->render('reponse_like/index.html.twig', [
            'controller_name' => 'ReponseLikeController',
        ]);
    }

    /**
     * @Route("/reponse/likeadd{idquestion}/{idreponse}/{reaction}", name="addreaction")
     */
    public function addreaction(ReponseRepository $reponseRepository, UserRepository $userRepository, $idquestion, $idreponse, $reaction)
    {
        $reponseLike = new ReponseLike();

        $reponse = $reponseRepository->find($idreponse);
        $reponseLike->setReponse($reponse);
        $reponseLike->setReaction($reaction);
        //$reponseLike->setUser($userRepository->find(1));

        $em = $this->getDoctrine()->getManager();
        $em->persist($reponseLike);
        $em->flush();

        return $this->redirectToRoute('reponse_index', array('idquestion' => $idquestion));

    }

    /**
     * @param EntityManagerInterface $manager
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/best",name="best")
     */
    public function back(EntityManagerInterface $manager)
    {
        $user = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $categorie = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Categorie c')->getSingleScalarResult();
        $question = $manager->createQuery('SELECT COUNT(q) FROM App\Entity\Question q')->getSingleScalarResult();
        $reponse = $manager->createQuery('SELECT COUNT(r) FROM App\Entity\Reponse r')->getSingleScalarResult();
        $bestquestion = $manager->createQuery(
            'SELECT q.nbrVue as nbrVue, q.question
             FROM App\Entity\Question q
              
             
             GROUP BY q
             ORDER BY nbrVue DESC'
        )
            ->setMaxResults(5)
            ->getResult();

        $worstquestion = $manager->createQuery(
            'SELECT q.nbrVue as nbrVue, q.question
             FROM App\Entity\Question q
              
             
             GROUP BY q
             ORDER BY nbrVue ASC'
        )
            ->setMaxResults(5)
            ->getResult();
        return $this->render("reponse/statistique.html.twig",
        array(
            'user'=>$user,
            'categorie'=>$categorie,
            'question'=>$question,
            'reponse'=>$reponse,
            'bestquestion'=>$bestquestion,
            'worstquestion'=>$worstquestion
        ));
    }

}