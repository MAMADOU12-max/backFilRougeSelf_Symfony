<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Profil;

final class ProfilPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * ProfilPersisiter constructor.
     */
    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository){
        $this->manager = $manager ;
        $this->userRepository = $userRepository ;
    }

    public function supports($data, array $context = []): bool {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = []) {
        // call your persistence layer to save $data

        $data->setLibelle($data->getLibelle()) ;
        $user = $this->manager->persist($data) ;
        $this->manager->flush($user);
        return $data;
    }

    public function remove($data, array $context = []){
        //id
        $id = $data->getId() ;
        // id profil users 
        $users = $this->userRepository->findBy(['profil'=>$id]) ;
        
        //archive profil    
        $data->setArchivage(1) ;
        $persist = $this->manager->persist($data);
        $this->manager->flush($persist);

        // parcourir users 
        foreach ($users as $value) {
            //archive each user
            $value->setArchivage(1) ;
            //persist
            $user = $this->manager->persist($value) ;
            $this->manager->flush($user);
        }

    }
}
