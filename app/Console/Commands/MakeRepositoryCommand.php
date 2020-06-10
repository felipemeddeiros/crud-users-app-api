<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class -- If you want to create UserRepository type just User';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->make('Repository', $this->getNameRepositoryInput(), '\Repositories\Eloquent', 'repository');
        $this->make('Repository Interface', $this->getNameInterfaceInput(), '\Repositories', 'repositoryInterface');
    }

    protected function make($type, $nameTarget, $namespaceDirectory, $fileName) {

        $name = $this->customQualifyClass($nameTarget, $namespaceDirectory);

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($nameTarget)) {
            $this->error("$type already exists!");

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->customBuildClass($name, $fileName)));

        $this->info("$type created successfully.");
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function customBuildClass($name, $stubFileName)
    {
        $stub = $this->files->get($this->customGetStub($stubFileName));

        return $this->replaceNamespace($stub, $name)
            ->customReplaceClass($stub, $name, $stubFileName);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function customGetStub($stubFileName)
    {
        return __DIR__.'/stubs/'.$stubFileName.'.stub';
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return class_exists($rawName);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameRepositoryInput()
    {
        return trim($this->getNameInput().'Repository');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInterfaceInput()
    {
        return trim($this->getNameInput().'RepositoryInterface');
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function customQualifyClass($name, $complement)
    {
        $name = ltrim($name, '\\/');

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->customGetDefaultNamespace(trim($rootNamespace, '\\'), $complement).'\\'.$name
        );
    }

     /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function customGetDefaultNamespace($rootNamespace, $complement = '')
    {
        return $rootNamespace.$complement;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function customReplaceClass($stub, $name, $stubFileName)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        $class = str_replace(ucfirst($stubFileName), '', $class);

        return str_replace('DummyClass', $class, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repositoryInterface.stub';
    }
}
