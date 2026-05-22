<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class IssueMachineTokenCommand extends Command
{
    protected $signature = 'sanctum:issue-machine-token
        {user_id : User ID that owns this machine token}
        {token_name=WGRCFP : Token name, e.g. WGRCFP}
        {--abilities=lms.read,lms.write : Comma-separated abilities}
        {--expires-days=30 : Expiration in days}';

    protected $description = 'Issue Sanctum machine token for external LMS API consumption';

    public function handle(): int
    {
        $user = User::query()->findOrFail((int) $this->argument('user_id'));
        $name = (string) $this->argument('token_name');
        $abilities = array_filter(array_map('trim', explode(',', (string) $this->option('abilities'))));
        $expiresAt = now()->addDays((int) $this->option('expires-days'));

        $token = $user->createToken($name, $abilities, $expiresAt);

        $this->line('Token created successfully. Store this once:');
        $this->line($token->plainTextToken);
        $this->newLine();
        $this->table(['token_name', 'abilities', 'expires_at'], [[
            $name,
            implode(', ', $abilities),
            $expiresAt->toDateTimeString(),
        ]]);

        return self::SUCCESS;
    }
}
