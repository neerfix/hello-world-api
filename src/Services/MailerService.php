<?php

namespace App\Services;

use App\Entity\User;
use DateTime;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private const EMAIL = 'noreply@space-cube.xyz';

    private const TEMPLATE_EMAIL_CONFIRMATION = 'emails/email_confirmation.html.twig';
    private const TEMPLATE_FORGET_PASSWORD = 'emails/email_forgetPassword.html.twig';

    // --------------------------------- >

    public function __construct(
        private MailerInterface $mailer,
        private TokenService $tokenServices,
    ) {
    }

    /**
     * @throws Exception
     */
    public function confirmationEmail(string $to, User $user): void
    {
        $template = static::TEMPLATE_EMAIL_CONFIRMATION;
        $expirationDate = new DateTime('+7 days');
        $subject = 'HelloWorld: Email vérification 🤓 ';

        $this->sendEmail($template, $to, $subject, $user, $expirationDate);
    }

    /**
     * @throws Exception
     */
    public function forgetPasswordEmail(string $to, User $user): void
    {
        $template = static::TEMPLATE_FORGET_PASSWORD;
        $expirationDate = new DateTime('+1 hour');
        $subject = 'HelloWorld: mot de passe oublié ? 😰';

        $this->sendEmail($template, $to, $subject, $user, $expirationDate);
    }

    // --------------------------------- >

    /**
     * @throws Exception
     */
    private function sendEmail(string $template, string $to, string $subject, User $user, DateTime $expirationDate): void
    {
        $token = $this->tokenServices->create($user);

        $email = (new TemplatedEmail())
            ->from(static::EMAIL)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context([
                'expiration_date' => $expirationDate,
                'username' => $user->getUsername(),
                'token' => $token->getValue(),
            ]);

        $email
            ->getHeaders()
            ->addTextHeader('MIME-Version', '1.0')
            ->addTextHeader('Content-type', 'text/html; charset=iso-8859-1');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e);
        }
    }
}
