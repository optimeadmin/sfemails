# SF Emails
Bundle para manejo de envio y contenido de correos.

## Instalación

```
composer require "optimeconsulting/sf-emails" "^1.0@dev"
```

## Configuración 

Agregar como un bundle en el `config/bundles.php`:

```php
<?php

return [
    ...
    Optime\Email\Bundle\OptimeEmailBundle::class => ['all' => true],
];
```

#### Configuración de opciones:

Crear/Ajustar el archivo `config/packages/optime_emails.yaml`:

```yaml
# Por ahora sin nada que agregar, no se necesita crea el archivo
```

Crear el archivo `config/routes/optime_emails.yaml`:

```yaml
optime_emails:
    resource: "@OptimeEmailBundle/Controller/"
    type:     annotation
    prefix:   /{_locale}/admin/emails
```

Correr comando de doctrine:

```
symfony console doctrine:schema:update -f
```

<hr>

## Uso

Ejemplo básico:

```php

use Optime\Email\Bundle\Service\Email\MailerFactory;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipient;

class XXXMailerSender
{
    public function __construct(
        private MailerFactory $factory
    ) {
    }

    public function send(User $user): void
    {
        $intent = $this->factory->create('template_code_xxx');
        
        $recipient = new EmailRecipient($user->getEmail(), $user->firstName());
        $variables = [
            'first_name' => $user->firstName(),
            'last_name' => $user->lastName(),
        ];

        $intent->send($variables, $recipient);
    }
}

Custom EmailApp:

```php

use Optime\Email\Bundle\Service\Email\MailerFactory;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipient;
use Optime\Email\Bundle\Repository\EmailAppRepository;

class XXXMailerSender
{
    public function __construct(
        private MailerFactory $factory,
        private EmailAppRepository $appRepository,
    ) {
    }

    public function send(User $user): void
    {
        $app = $this->appRepository->find(3);
        $intent = $this->factory->create('template_code_xxx', $app);
        
        $recipient = new EmailRecipient($user->getEmail(), $user->firstName());
        $variables = [
            'first_name' => $user->firstName(),
            'last_name' => $user->lastName(),
        ];

        $intent->send($variables, $recipient);
    }
}

```

Varios usuarios:

```php

use Optime\Email\Bundle\Service\Email\MailerFactory;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipient;
use Optime\Email\Bundle\Repository\EmailAppRepository;

class XXXMailerSender
{
    public function __construct(
        private MailerFactory $factory,
        private EmailAppRepository $appRepository,
    ) {
    }

    public function send(User $userA, User $userB, User $userC): void
    {
        $app = $this->appRepository->find(3);
        $intent = $this->factory->create('template_code_xxx', $app);
        
        foreach([$userA, $userB, $userC] as $user) {
                    
            $recipient = new EmailRecipient($user->getEmail(), $user->firstName());
            
            $variables = [
                'first_name' => $user->firstName(),
                'last_name' => $user->lastName(),
            ];

            $intent->send($variables, $recipient);
        }
    }
}

```
