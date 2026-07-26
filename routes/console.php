<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('security:check', function (): int {
    $checks = [
        'app_key_configurada' => (string) config('app.key') !== '',
        'debug_desabilitado_em_producao' => ! app()->environment('production') || ! (bool) config('app.debug'),
        'cookie_http_only' => (bool) config('session.http_only'),
        'sessao_criptografada' => (bool) config('session.encrypt'),
        'composer_lock_presente' => is_file(base_path('composer.lock')),
    ];
    $falhas = array_keys(array_filter($checks, static fn (bool $resultado): bool => ! $resultado));
    $report = [
        'executado_em' => now()->toIso8601String(),
        'ambiente' => app()->environment(),
        'checks' => $checks,
        'falhas' => $falhas,
    ];

    Storage::disk('local')->put('security/security-report-'.now()->format('Ymd-His').'.json', json_encode($report, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));

    if ($falhas !== [] && (string) config('services.security_alert.webhook_url') !== '') {
        Http::timeout(10)->post((string) config('services.security_alert.webhook_url'), $report);
    }

    $this->table(['Check', 'Status'], collect($checks)->map(fn (bool $resultado, string $nome): array => [$nome, $resultado ? 'OK' : 'FALHA'])->all());

    return $falhas === [] ? self::SUCCESS : self::FAILURE;
})->purpose('Executa verificacoes basicas de seguranca e gera relatorio');

app(Schedule::class)->command('security:check')->dailyAt('03:00');

Artisan::command('access:create-super-admin {email}', function (string $email): int {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Informe um e-mail valido.');

        return self::FAILURE;
    }

    $nome = $this->ask('Nome do super administrador');
    $senha = $this->secret('Senha temporaria (minimo 8 caracteres)');
    if (! is_string($senha) || strlen($senha) < 8) {
        $this->error('A senha deve possuir pelo menos 8 caracteres.');

        return self::FAILURE;
    }

    $papelId = \App\Models\Papel::query()->updateOrCreate(
        ['slug' => 'super_admin_salta'],
        ['nome' => 'Super administrador Salta Digital', 'escopo' => 'salta_admin', 'ativo' => true],
    )->id;
    $usuario = \App\Models\User::query()->updateOrCreate(
        ['email' => $email],
        ['nome' => $nome, 'password' => $senha, 'ativo' => true],
    );
    $usuario->papeis()->sync([$papelId]);
    $usuario->tenants()->detach();
    $this->info('Super administrador criado/atualizado sem vinculo operacional de cliente.');

    return self::SUCCESS;
});
