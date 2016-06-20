<?php
declare(strict_types = 1);

namespace Soliant\FormidableBootstrap\Extension;

use Interop\Container\ContainerInterface;

final class InputFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new Input(
            $container->get('Soliant.FormidableBootstrap.ErrorFormatter')
        );
    }
}
