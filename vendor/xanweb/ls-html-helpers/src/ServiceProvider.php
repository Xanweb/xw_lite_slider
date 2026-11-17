<?php

namespace Xanweb\Ls\HtmlHelper;

use Xanweb\LsCommon\Service\Provider as FoundationServiceProvider;
use Xanweb\Ls\HtmlHelper\Service\Form;

class ServiceProvider extends FoundationServiceProvider
{
    public function _register(): void
    {
        $this->app->bind(\Concrete\Core\Form\Service\Form::class, Form::class);
        $this->app->singleton('helper/form', Form::class);
        $this->app->singleton('helper/form/ios_toggler', fn ($app) => new Service\Form\IosTogglerWidget($app['helper/form']));
    }
}
