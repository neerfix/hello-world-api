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
use RuntimeException;

class UserService
{
    // ------------------------- >

    public const MIN_PASSWORD_LENGTH = 8;

    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
        private TextService $textService,
        private MailerService $mailerService,
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

        $existingEmail = $this->userRepository->findOneByEmail($email);

        // Existing email
        if (null !== $existingEmail) {
            throw new Exception('cet email est déjà utilisé');
        }

        $existingUsername = $this->userRepository->findOneByUsername($username);

        if (null !== $existingUsername) {
            throw new Exception('cet username est déjà utilisé');
        }

        $birthdate = (clone $birthdate)
            ->setTime(0, 0, 0);

        if ($birthdate > new DateTime()) {
            throw new Exception('La date de naissance n\'est pas valide', 'users.create.birthdate.invalid', 'birthdate');
        }

        // Invalid password
        if (strlen($password) < static::MIN_PASSWORD_LENGTH) {
            throw new Exception('le mot de passe est trop court. Il doit faire au minimum '.static::MIN_PASSWORD_LENGTH.' caractère');
        }

        $user = (new User())
            ->setEmail($email)
            ->setUsername($username)
            ->setPassword('tmp-pwd')
            ->setDateOfBirth($birthdate)
            ->setUuid(Uuid::uuid4())
            ->setIsVerify(false);

        if (null !== $firstname) {
            $user->setFirstname($firstname);
        }

        if (null !== $lastname) {
            $user->setFirstname($lastname);
        }

        $this->em->persist($user);
        $this->em->flush();

        $this->updatePassword($user, $password);
        $this->mailerService->confirmationEmail($email, $user);

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
     * @throws NonUniqueResultException
     * @throws Exception
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('l\'email n\'est pas valide', 'users.create.email.invalid', 'email');
        }

        $existingEmail = $this->userRepository->findOneByEmail($email);

        if ((null !== $existingEmail) && $user->getEmail() !== $existingEmail->getEmail()) {
            throw new Exception('cet email est déjà utilisé');
        }

        $birthdate = (clone $birthdate)
            ->setTime(0, 0, 0);

        if ($birthdate > new DateTime()) {
            throw new RuntimeException('La date de naissance n\'est pas valide', 'users.create.birthdate.invalid', 'birthdate');
        }

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

        if (in_array(User::ROLE_ADMIN, $loggedUser->getRoles(), true)) {
            $user->setStatus(User::STATUS_BANNED);
        } else {
            $user->setStatus(User::STATUS_DELETED);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
