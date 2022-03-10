<?php

namespace App\Services;

use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

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
}
