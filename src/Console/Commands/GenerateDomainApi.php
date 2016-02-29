<?php

namespace Skimia\ApiFusion\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class GenerateDomainApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-fusion:generate.domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'génère toute les classes necessaires pour gérér une nouvelle resource';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $camelCasedSingularName = $this->ask('Nom de la resource en camelCase [maNouvelleResourceEnCamelCase]');
        $ucFirstSingularName = ucfirst($camelCasedSingularName);
        $camelCasedPluralName = str_plural($camelCasedSingularName);
        $ucFirstPluralName = str_plural($ucFirstSingularName);

        //Todo : proposer plusieurs choix en fonction des modules/packages au lieu que le seul namespace app
        $namespace = $this->ask('Namespace du domaine de la resource', 'App\Domain\\'.$ucFirstSingularName);
        //Todo : proposer plusieurs choix en fonction des modules/packages au lieu que le seul dossier app
        $directory = $this->ask('Répertoire de la resource', 'app/Domain/'.$ucFirstSingularName);

        //Todo : proposer plusieurs choix en fonction des modules/packages au lieu que le seul namespace app
        $namespaceController = $this->ask('Namespace du controller de la resource', 'App\Http\Controllers\Api\v1');
        //Todo : proposer plusieurs choix en fonction des modules/packages au lieu que le seul dossier app
        $directoryController = $this->ask('Répertoire du controlleur', 'app/Http/Controllers/Api/v1');

        $this->generateModel($namespace, $directory, $ucFirstSingularName);

        $this->info('Model généré !');

        $this->generateValidator($namespace, $directory, $ucFirstSingularName);

        $this->info('Validator généré !');

        $this->generateService($namespace, $directory, $ucFirstSingularName);

        $this->info('Service généré !');

        $this->generateTransformer($namespace, $directory, $ucFirstSingularName, $camelCasedSingularName, $camelCasedPluralName);

        $this->info('Transformer généré !');

        $this->generateController($namespaceController, $directoryController, $ucFirstPluralName, $namespace, $ucFirstSingularName, $camelCasedPluralName);

        $this->info('Controller généré !');
        //$this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
    }

    public function generateModel($namespace, $directory, $name)
    {
        $contents = $this->getStub('resource_model');

        $contents = str_replace(
            [
                '{{NAMESPACE}}',
                '{{CLASS}}',
            ],
            [
                $namespace,
                $name,
            ], $contents);

        $this->write($directory.'/'.$name.'.php', $contents);
    }

    public function generateValidator($namespace, $directory, $name)
    {
        $contents = $this->getStub('resource_validator');

        $contents = str_replace(
            [
                '{{NAMESPACE}}',
                '{{CLASS}}',
            ],
            [
                $namespace,
                $name,
            ], $contents);

        $this->write($directory.'/'.$name.'Validator.php', $contents);
    }

    public function generateService($namespace, $directory, $name)
    {
        $contents = $this->getStub('resource_service');

        $contents = str_replace(
            [
                '{{NAMESPACE}}',
                '{{CLASS}}',
            ],
            [
                $namespace,
                $name,
            ], $contents);

        $this->write($directory.'/'.$name.'Service.php', $contents);
    }

    public function generateTransformer($namespace, $directory, $name, $camel, $camelplu)
    {
        $contents = $this->getStub('resource_transformer');

        $contents = str_replace(
            [
                '{{NAMESPACE}}',
                '{{CLASS}}',
                '{{NAME}}',
                '{{NAME_PLU}}',
            ],
            [
                $namespace,
                $name,
                $camel,
                $camelplu,
            ], $contents);

        $this->write($directory.'/'.$name.'Transformer.php', $contents);
    }

    public function generateController($namespaceCtrl, $directory, $nameCtrl, $namespace, $class, $pluriel)
    {
        $contents = $this->getStub('resource_controller');

        $contents = str_replace(
            [
                '{{NAMESPACE_CTRL}}',
                '{{CLASS_CTRL}}',
                '{{NAMESPACE}}',
                '{{CLASS}}',
                '{{URL}}',
            ],
            [
                $namespaceCtrl,
                $nameCtrl,
                $namespace,
                $class,
                $pluriel,
            ], $contents);

        $this->write($directory.'/'.$nameCtrl.'Controller.php', $contents);
    }

    protected function getStub($name)
    {
        $path = __DIR__.'/stubs/'.$name.'.stub';

        return \File::get($path);
    }

    protected function write($path, $content)
    {
        $dir = dirname($path);

        if (! \File::exists($dir)) {
            \File::makeDirectory($dir, 0777, true);
        }
        \File::put($path, $content);
    }
}
