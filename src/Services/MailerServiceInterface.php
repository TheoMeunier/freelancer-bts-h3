<?php

declare(strict_types=1);

namespace App\Services;

interface MailerServiceInterface
{
    public function send(string $from, string $to, string $subject, string $htmlTemplate, string $textTemplate, array $params): void;
}
