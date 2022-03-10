<?php

namespace App\Services;

use App\Entity\File;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class FileService
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function create(string $path, User $user): File
    {
        $path = trim($path);

        $file = (new File())
            ->setPath($path)
            ->setStatus(File::STATUS_ACTIVE)
            ->setUuid(Uuid::uuid4())
            ->setUserId($user);

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }

    public function delete(File $file, User $user): File
    {
        if (File::STATUS_DELETED === $file->getStatus()) {
            throw new RuntimeException('Le fichier est déjà supprimé');
        }
        if (!in_array(User::ROLE_ADMIN, $user->getRoles(), true) && $file->getUserId() !== $user->getId()) {
            throw new RuntimeException('Vous n\'avez pas l\'autorisation de supprimer ce fichier');
        }

        $file
            ->setStatus(File::STATUS_DELETED)
            ->setUpdatedAt(new DateTime());

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }
}
