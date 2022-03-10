<?php

namespace App\Services;

use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;

class FileService
{
    public function __construct(
        EntityManagerInterface $em,
        private FileRepository $fileRepository
    ){}

}