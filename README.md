# SF Emails
Bundle para manejo de envio y contenido de correos.

## Instalación

```
composer require "manuelj555/sf-emails" "^1.0@dev"
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