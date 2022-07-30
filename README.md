# SF Emails
Bundle para manejo de envio y contenido de correos.

## Instalación

```
composer require "optimeconsulting/sf-emails" "^2.0@dev"
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

#### Crear entidad EmailApp

La clase EmailApp debe implementar Optime\Email\Bundle\Entity\EmailAppInterface:

```php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Entity\EmailAppInterface;

#[ORM\Entity]
class EmailApp implements EmailAppInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}
```

Agregar configuracion de la entidad EmailApp en el `config/packages/doctrine.yaml`:

```yaml
doctrine:
    ...
    orm:
        ...
        resolve_target_entities:
            Optime\Email\Bundle\Entity\EmailAppInterface: App\Entity\EmailApp

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
use App\Repository\EmailAppRepository;

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
use App\Repository\EmailAppRepository;

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

#### App Resolver:

```php

use Optime\Email\Bundle\Service\Email\MailerFactory;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipient;
use App\Repository\EmailAppRepository;
use Optime\Email\Bundle\Service\Email\App\EmailAppResolver;

class XXXMailerSender
{
    public function __construct(
        private MailerFactory $factory,
        private EmailAppRepository $appRepository,
    ) {
    }

    public function send(Event $event, User $user): void
    {
        $appResolver = new EmailAppResolver(function() use ($event) {
            return $this->appRepository->findByEvent($event)
        });
    
        $intent = $this->factory->create('template_code_xxx', $appResolver);
        
        $recipient = new EmailRecipient($user->getEmail(), $user->firstName());
        $variables = [
            'first_name' => $user->firstName(),
            'last_name' => $user->lastName(),
        ];

        $intent->send($variables, $recipient);
    }
}

```
