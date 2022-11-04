# SF Emails
Bundle para manejo de envio y contenido de correos.

## Instalación

```
composer require "optimeconsulting/sf-emails" "^3.0@dev"
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

**Tener en cuenta que si se están usando anotaciones para el proyecto, la entidad EmailApp tambien debe usar anotaciones en vez de atributos de php.**

<hr/>

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

### Importante

Este bundle requiere del bundle de sfutils, el se va a instalar automáticamente. De todas formas será necesario configurar dicho bundle siguiendo su [documentación](https://github.com/optimeadmin/sf_utils/blob/master/README.md).

Tambien será necesario instalar y configurar las extensiones de doctrine, especificamente la de traducciones, para ello seguir la documentación del bundle [StofDoctrineExtensionsBundle](https://symfony.com/bundles/StofDoctrineExtensionsBundle/current/index.html)

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
```

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
                '_locale' => $user->getLocale(), // opcional, si se pasa se usa ese valor para los textos, y si no, se usa el locale de la petición actual.
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
