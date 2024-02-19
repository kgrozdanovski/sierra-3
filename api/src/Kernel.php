<?php

namespace App;

use App\Kernel\Business\DependencyInjection\ServiceResolverInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(ServiceResolverInterface::class)
            ->addTag('app.kernel.dependency_injection.service_resolver');
    }
}
