<?php

declare(strict_types=1);

namespace Sparkify\Core\Mail;

use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

final class Mailer
{
	private SymfonyMailer $mailer;

	public function __construct(string $dsn)
	{
		$transport = Transport::fromDsn($dsn);
		$this->mailer = new SymfonyMailer($transport);
	}

	public function send(string $to, string $subject, string $html, ?string $from = null): void
	{
		$email = (new Email())
			->to($to)
			->subject($subject)
			->html($html);
		if ($from) { $email->from($from); }
		$this->mailer->send($email);
	}
}