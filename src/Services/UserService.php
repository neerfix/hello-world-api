<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Ramsey\Uuid\Uuid;

class UserService
{
    // ------------------------- >

    public const MIN_PASSWORD_LENGTH = 8;

    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TextService $textService,
        private TokenRepository $tokenRepository,
    ) {
    }

    // ------------------------- >

    /**
     * @throws Exception
     */
    public function create(
        string $email,
        string $username,
        string $password,
        DateTime $birthdate,
        ?string $firstname = null,
        ?string $lastname = null,
    ): User {
        $email = $this->textService->cleanEmail($email);
        $firstname = !empty($firstname) ? $this->textService->cleanFirstName($firstname) : null;
        $lastname = !empty($lastname) ? $this->textService->cleanLastName($lastname) : null;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('l\'email n\'est pas valide', 'users.create.email.invalid', 'email');
        }

        $existingUser = $this->userRepository->findOneByEmail($email);

        if (!empty($birthdate)) {
            $birthdate = (clone $birthdate)
                ->setTime(0, 0, 0);

            if ($birthdate > new DateTime()) {
                throw new Exception('La date de naissance n\'est pas valide', 'users.create.birthdate.invalid', 'birthdate');
            }
        }

        // Invalid password
        if (empty($password) || $password < static::MIN_PASSWORD_LENGTH) {
            throw new Exception('le mot de passe est trop court. Il doit faire au minimum '.static::MIN_PASSWORD_LENGTH.' caractère', '', 'password');
        }

        // Existing email
        if (!empty($existingUser)) {
            throw new Exception('Cet email existe déjà', 'users.create.email.existing', 'email');
        }

        $user = (new User())
            ->setEmail($email)
            ->setUsername($username)
            ->setPassword('tmp-pwd')
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setDateOfBirth($birthdate)
            ->setUuid(Uuid::uuid4())
            ->setIsVerify(false);

        $this->em->persist($user);
        $this->em->flush();

        $this->updatePassword($user, $password);

        return $user;
    }

    public function updatePassword(User $user, string $password): User
    {
        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user->setPassword($encryptedPassword);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function getUserByToken(Token $token): User
    {
        $token = $this->tokenRepository->findOneByValue($token->getValue());

        if (null === $token) {
            throw new Exception('Le Token est invalide ou non trouvé');
        }

        if ($token->getExpirationDate() < new DateTime()) {
            throw new Exception('Le Token est invalide ou a expiré');
        }

        return $token->getUser();
    }

    public function update()
    {
    }

    public function delete(User $user): User
    {
    }
}
