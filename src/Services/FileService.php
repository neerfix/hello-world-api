<?php

namespace App\Services;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class FileService
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function create(string $path): File
    {
        $path = trim($path);

        $file = (new File())
            ->setPath($path)
            ->setStatus(File::STATUS_ACTIVE)
            ->setUuid(Uuid::uuid4());

        $this->em->persist($file);
        $this->em->flush();

        return $file;
    }
}
