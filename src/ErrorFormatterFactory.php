<?php
declare(strict_types = 1);

namespace Soliant\FormidableBootstrap;

use Interop\Container\ContainerInterface;

final class ErrorFormatterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $messages = [];

        if ($container->has('config')) {
            $config = $container->get('config');

            if (isset($config['soliant_formidable_bootstrap']['messages'])) {
                $messages = $config['soliant_formidable_bootstrap']['messages'];
            }
        }

        return new \DASPRiD\Formidable\Helper\ErrorFormatter($messages);
    }
}
