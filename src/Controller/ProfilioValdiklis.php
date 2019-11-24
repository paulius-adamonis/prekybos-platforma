<?php

namespace App\Controller;

use App\Entity\Vartotojas;
use App\Entity\VartotojoAtsiliepimas;
use App\Form\VartotojoAtsiliepimasType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilioValdiklis extends AbstractController
{

    /**
     * @Route("/vartotojas", name="atsiliepimai")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $loggedUser = $this->getUser();

        $getRatings = $this->getDoctrine()->getRepository(VartotojoAtsiliepimas::class)->findBy(
            array(
                'fkGavejas' => $loggedUser
            ),
            array(
                'id' => 'desc'
            )
        );
        $averageRating = $this->getDoctrine()->getRepository(VartotojoAtsiliepimas::class)->getAverageRating($loggedUser);
        $allRatings = $paginator->paginate(
            $getRatings,
            $request->query->getInt('page', 1),
            4
        );
        $allRatings->setCustomParameters(array(
            'align' => 'center'
        ));


        return $this->render('profilis/index.html.twig', [
            'title' => 'Atsiliepimai',
            'user' => $loggedUser,
            'averageRating' => $averageRating,
            'allRatings' => $allRatings,
            'tikrinam' => false
        ]);

    }

    /**
     * @Route("/vartotojas/id={id}", name="atsiliepimaiId")
     * @ParamConverter("user", class="App\Entity\Vartotojas", options={"id" = "id"})
     * @Method({"GET", "POST"})
     */
    public function getUserProfile(Request $request, Vartotojas $user = null, PaginatorInterface $paginator, \Swift_Mailer $mailer)
    {
        $loggedUser = $this->getUser();
        if ($user !== null) {

            $getRatings = $this->getDoctrine()->getRepository(VartotojoAtsiliepimas::class)->findBy(
                array(
                    'fkGavejas' => $user
                ),
                array(
                    'id' => 'desc'
                )
            );
            $averageRating = $this->getDoctrine()->getRepository(VartotojoAtsiliepimas::class)->getAverageRating($user);
            $allRatings = $paginator->paginate(
                $getRatings,
                $request->query->getInt('page', 1),
                4
            );
            $allRatings->setCustomParameters(array(
                'align' => 'center'
            ));
            if($user != $loggedUser){
                $newRating = new VartotojoAtsiliepimas();
                $form = $this->createForm(VartotojoAtsiliepimasType::class, $newRating, array());

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $newRating = $form->getData();

                    $newRating->setData(new \DateTime());
                    $newRating->setFkRasytojas($loggedUser);
                    $newRating->setFkGavejas($user);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($newRating);
                    $entityManager->flush();
                    $this->sendNotificationAboutRating($user->getElPastas(), $user, $mailer);
                    return $this->redirect($request->getUri());

                }
                return $this->render('profilis/index.html.twig', [
                    'title' => 'Atsiliepimai',
                    'user' => $user,
                    'form' => $form->createView(),
                    'averageRating' => $averageRating,
                    'allRatings' => $allRatings,
                    'tikrinam' => true
                ]);
            }else{

                return $this->render('profilis/index.html.twig', [
                    'title' => 'Atsiliepimai',
                    'user' => $user,
                    'averageRating' => $averageRating,
                    'allRatings' => $allRatings,
                    'tikrinam' => false
                ]);
            }

        }
        return $this->redirectToRoute('app_main');
    }

    /**
     * @Route("/vartotojas/zinutes", name="zinutes")
     * @IsGranted("ROLE_USER")
     */
    public function showAllConversations()
    {
        return $this->render('profilis/zinutes.html.twig');

    }

    public function sendNotificationAboutRating($email, Vartotojas $user, \Swift_Mailer $mailer){
        $message = (new \Swift_Message('Pranešimas apie naują atsiliepimą'))
            ->setFrom(['paumanma@gmail.com' => 'PAM^3 komanda'])
            ->setTo($email)
            ->setBody(
                $this->renderView('emails/naujasAtsiliepimas.html.twig', array(
                    'vartotojas' => $user,
                )),
                'text/html'
            );
        if (!$mailer->send($message, $failures)) {
            return false;
        }else{
            return true;
        }
    }
}
