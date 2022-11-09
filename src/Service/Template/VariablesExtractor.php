<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Symfony\Component\Yaml\Yaml;
use function array_filter;
use function array_map;
use function in_array;
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

        preg_match_all('/(\{\{\s*(?!\}\})(.(?!\}\}))+\s*\}\})/mi', $content, $matches);

        $vars = array_map(fn($item) => str_replace(' ', '', $item), $matches[1] ?? []);

        return array_filter($vars, function ($var) {
            return !in_array($var, [
                    '{{content}}',
                    '{{app.request.schemeAndHttpHost}}',
                ])
                /*&& !preg_match('/\{\{[a-zA-Z0-9]+\(/', $var)*/;
        });
    }

    public function extractAndClean(EmailTemplate $template): array
    {
        $vars = $this->extract($template);

        return array_map(fn($item) => trim($item, '{}'), $vars);
    }

    public function extractAsYaml(EmailTemplate $template, array $prependVars = []): string
    {
        $vars = $this->extractAndClean($template);
        $json = [];

        foreach ($vars as $varName) {
            $json[$varName] = '';
        }

        unset($json[EmailLog::UUID_VARIABLE]);

        return Yaml::dump($json + $prependVars);
    }

    public function buildVarsFromYaml(string $yamlContent): array
    {
        return Yaml::parse($yamlContent);
    }
}