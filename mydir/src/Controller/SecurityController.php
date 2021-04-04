<?php

namespace App\Controller;

use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
            return $this->redirectToRoute('afficheE');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
//        return $this->redirectToRoute('afficheE');

        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @param Request $request
     * @param UserRepository $users
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     * @Route("/oubli-pass", name="app_forgotten_password")
     */

    public function oubliPass(Request $request, UserRepository $users, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
       $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $donnees = $form->get('email')->getData();
            $user = $users->findOneBySomeField($donnees);
            if ($user == null) {
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            }
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetCode($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('votre@adresse.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe cliquer ici :" . $url,
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }


    /**
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_code' => $token]);
        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }
        if ($request->isMethod('POST')) {
            $user->setResetcode(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('message', 'Mot de passe mis à jour');
            return $this->redirectToRoute('app_login');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
