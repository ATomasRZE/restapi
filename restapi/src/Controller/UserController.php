<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

/**
 * @Route("/api", name="api_")
 */
class UserController extends AbstractController
{
    /**
     * Vrátí seznam všech uživatelů
     * 
     * Parametry:
     * 
     * Lze řadit podle username -> order=asc/desc
     * 
     * Lze filtrovat podle username -> username=name
     * 
     * Lze stránkovat -> page=1-n
     * 
     * např: ?order=asc&username=name&page=1
     * 
     * @Route("/user", name="user_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        define("ITEMS_PER_PAGE",10);

        $order = $request->query->get('order');
        $username = $request->query->get('username');
        $page = $request->query->get('page');

        $sql = $this->getDoctrine()
            ->getRepository(User::class)
            ->createQueryBuilder('a');

        if(isset($username))
        {
            $sql = $sql->andWhere('a.username = :value')
                ->setParameter('value', $username);
        }
        if(isset($order))
        {
            $sql = $sql->orderBy('a.username',$order == 'desc' ? 'desc' : 'asc');
        }
        if(isset($page))
        {
            $sql = $sql->setFirstResult(($page-1)*ITEMS_PER_PAGE)
                ->setMaxResults(ITEMS_PER_PAGE);
        }

        $users = $sql->getQuery()
            ->execute();

        $data = [];
 
        foreach ($users as $item) 
        {
           $data[] = [
               'id' => $item->getId(),
               'username' => $item->getUsername(),
           ];
        }

        return $this->json($data);
    }

    /**
     * Zobrazí detail daného uživatele podle jeho id
     * 
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
 
        if(!$user) return $this->json('Nenalezen uživatel s id ' . $id, 404);
 
        $data =  [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
        ];
         
        return $this->json($data);
    }

    /**
     * Vytvoří nového uživatele
     * 
     * @Route("/user/{username}/{password}/{roles}", name="user_new", methods={"POST"})
     */
    public function new(string $username, string $password, string $roles): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
 
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(array($roles));
 
        $entityManager->persist($user);
        $entityManager->flush();
 
        return $this->json('Nový uživatel vytvořen s id ' . $user->getId());
    }

    /**
     * Aktualizuje údaje o uživateli podle id
     * 
     * @Route("/user/{id}/{username}/{password}/{roles}", name="user_edit", methods={"PUT"})
     */
    public function edit(int $id,string $username, string $password, string $roles): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
 
        if (!$user) return $this->json('Nenalezen uživatel s id ' . $id, 404);
 
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(array($roles));
        $entityManager->flush();
 
        $data =  [
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'password' => $user->getPassword(),
            'roles' => $user->getRoles(),
        ];
         
        return $this->json($data);
    }
 
    /**
     * Smaže uživatele
     * 
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
 
        if (!$user) return $this->json('Nenalezen uživatel s id ' . $id, 404);
 
        $entityManager->remove($user);
        $entityManager->flush();
 
        return $this->json('Uživatel smazán s id ' . $id);
    }
}
