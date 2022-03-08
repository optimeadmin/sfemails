<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailTemplate;
use function array_filter;
use function array_map;
use function in_array;
use function preg_match;
use function str_replace;

/**
 * @author Manuel Aguirre
 */
class VariablesExtractor
{
    public function extract(EmailTemplate $template): array
    {
        $content = $template->getConfig()?->getLayout()?->getContent() ?? '';
        $content .= $template->getContent() ?? '';

        preg_match_all('/(\{\{\s*[^\}]+\s*\}\})/', $content, $matches);

        $vars = array_map(fn($item) => str_replace(' ', '', $item), $matches[1] ?? []);

        return array_filter($vars, function ($var) {
            return !in_array($var, [
                    '{{content}}',
                    '{{app.request.schemeAndHttpHost}}',
                ])
                && !preg_match('/\{\{[a-zA-Z0-9]+\(/', $var);
        });
    }
}