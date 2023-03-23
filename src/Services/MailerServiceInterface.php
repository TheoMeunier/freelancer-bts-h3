<?php

namespace App\Services;

interface MailerServiceInterface
{
    public function send(string $from, string $to, string $subject, string $htmlTemplate, string $textTemplate, array $params);
}