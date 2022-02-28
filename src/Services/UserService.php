<?php

namespace App\Services;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;

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

        $this->getExceptionToValidationCreateOrUpdateUser($email, $username, $birthdate, $password);

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
            throw new RuntimeException('Le Token est invalide ou non trouvé');
        }

        if ($token->getExpirationDate() < new DateTime()) {
            throw new RuntimeException('Le Token est invalide ou a expiré');
        }

        return $token->getUser();
    }

    /**
     * @throws TransactionRequiredException
     * @throws NonUniqueResultException
     */
    public function update(
        User $user,
        string $email,
        string $username,
        DateTime $birthdate,
        ?string $firstname = null,
        ?string $lastname = null,
    ): User {
        $email = $this->textService->cleanEmail($email);
        $firstname = !empty($firstname) ? $this->textService->cleanFirstName($firstname) : null;
        $lastname = !empty($lastname) ? $this->textService->cleanLastName($lastname) : null;

        $this->getExceptionToValidationCreateOrUpdateUser($email, $username, $birthdate, $password, $user);

        $user
            ->setEmail($email)
            ->setUsername($username)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setDateOfBirth($birthdate);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @throws Exception
     */
    public function delete(User $user, User $loggedUser): User
    {
        if (User::STATUS_ACTIVE !== $user->getStatus()) {
            throw new RuntimeException('L\'utilisateur est déjà supprimé');
        }

        if (in_array(User::RANK_ADMIN, $loggedUser->getRoles(), true)) {
            $user->setStatus(User::STATUS_BANNED);
        } else {
            $user->setStatus(User::STATUS_DELETED);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @throws TransactionRequiredException
     * @throws NonUniqueResultException
     * @throws Exception
     */
    private function getExceptionToValidationCreateOrUpdateUser(
        string $email,
        string $username,
        DateTime $birthdate,
        ?string $password = null,
        ?User $user = null
    ): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('l\'email n\'est pas valide', 'users.create.email.invalid', 'email');
        }

        $existingUser = $this->userRepository->findOneByEmail($email);

        // Existing email
        if (null !== $existingUser) {
            return;
        }

        if (null === $user) {
            $existingUsername = $this->userRepository->findOneByUsername($username);

            if (null !== $existingUsername) {
                return;
            }
        }

        $birthdate = (clone $birthdate)
            ->setTime(0, 0, 0);

        if ($birthdate > new DateTime()) {
            throw new RuntimeException('La date de naissance n\'est pas valide', 'users.create.birthdate.invalid', 'birthdate');
        }

        if (null !== $password) {
            // Invalid password
            if (empty($password) || $password < static::MIN_PASSWORD_LENGTH) {
                throw new RuntimeException('le mot de passe est trop court. Il doit faire au minimum '.static::MIN_PASSWORD_LENGTH.' caractère', '', 'password');
            }
        }
    }
}
