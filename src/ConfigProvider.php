<?php
declare(strict_types = 1);

namespace Soliant\FormidableBootstrap;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return require __DIR__ . '/../config/config.php';
    }
}
