<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordType;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator,\Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setRoles(["ROLE_USER"]);
            $random = random_int(2, 10000);
            $user->code = $random;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Activation du compte'))
                // On attribue l'expéditeur
                ->setFrom('azizhammami621@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'registration/activation.html.twig', ['code' => $user->code]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );



        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @param UserRepository $repository
     * @return Response
     * @Route ("/profile",name="profile")
     */
    public function affichetheUser(UserRepository $repository)
    {

        $user =$this->getUser();
        return $this->render('utilis/profil.html.twig', ['user' => $user]);
    }

    /**
     * @param UserRepository $repository
     * @return Response
     * @Route ("/afficheU",name="afficheU")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function afficheUser(UserRepository $repository)
    {

        $user = $repository->findAll();
        return $this->render('utilis/afficheU.html.twig', ['user' => $user]);
    }

    /**
     * @param UserRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("user/updateUser.twig.html/{id}", name="updateU")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifier(UserRepository $repository, $id, Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $repository->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheU');
        }
        return $this->render('utilis/updateUser.html.twig', ['form' => $form->createView()

        ]);


    }
    /**
     * @param UserRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
         * @Route ("user/update.twig.html/{id}", name="updateprofile")
     */
    public function modifierProfil(UserRepository $repository, $id, Request $request,UserPasswordEncoderInterface $passwordEncoder,\Swift_Mailer $mailer)
    {
        $user = $repository->find($id);
        $form = $this->createForm(ProfileType::class, $user);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $random = random_int(2, 10000);
            $user->code = $random;
            $message = (new \Swift_Message('Activation du compte'))
                // On attribue l'expéditeur
                ->setFrom('azizhammami621@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'registration/activation.html.twig', ['code' => $user->code]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheE');
        }
        return $this->render('utilis/updateprofile.html.twig', ['form' => $form->createView()

        ]);


    }

    /**
     * @param $id
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppU/{id}",name="dprofile")
     */
    public function deleteProfile(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute('afficheU');
    }
    /**
     * @param $id
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/suppU/{id}",name="dU")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteEvent(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute('afficheU');
    }
    /**
     * @Route("/activation/{code}", name="activation")
     */
    public function activation($code, UserRepository $users)
    {
        $user = $users->findOneBy(['code' => $code]);

         if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
         }


        $user->code=1;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


        $this->addFlash('message', 'Utilisateur activé avec succès');


        return $this->redirectToRoute('afficheE');
    }











}




