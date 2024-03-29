<?php namespace Prezire\FacadeGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Artisan;

final class FacadeGenerator extends Command
{
  private $aliasName, $target;

  /**
   * The name and signature of the console command.
   * @var string
   */
  protected $signature = 'make:facade 
                          {alias-name : The alias name of the facade class.}
                          {target : The fully qualified namespace of the service class to create a facade from. For example: \\\App\\\Services\\\Foo.}';

  /**
   * The console command description.
   * @var string
   */
  protected $description = 'Create a new Service Provider and a Facade class.';
  
  private function providersPath(string $filename):string
  {
    return $this->appPath("Providers/Facades/{$filename}");
  }
  
  private function appPath(string $file):string
  {
    return base_path("app/{$file}");
  }
  
  private function providerFullPath():string
  {
    return $this->providersPath($this->aliasName . '.php');
  }

  private function kebabedAliasName():string
  {
    return Str::kebab($this->aliasName);
  }

  private function classBaseName():string
  {
    return class_basename($this->target);
  }

  private function svcExists():bool
  {
    $t = array_slice(explode('\\', $this->target), 2);
    $t = implode('/', $t) . '.php';
    return file_exists($this->appPath($t));
  }
  
  private function createProvider()
  {
    if(!$this->svcExists()) 
      return $this->error('The service file does not exist. Create it first.');

    $b = true;
    if(file_exists($this->providerFullPath()))
      $b = $this->confirm('A facade file already exists. Continue?');

    if($b)
    {
      $provider = $this->genProvider() . '::class';
      $this->replaceProviderCnts();
      $fqnFacade = $this->createFacade();
      $alias = $this->aliasName;
      $this->info('Register in config.app:');
      $this->info("*  Service provider {$provider}");
      $this->info("*  Alias '{$alias}' => {$fqnFacade}");
    }
  }

  /**
   * @return  string  Fully qualified name of the created file.
  */
  private function createFacade():string
  {
    $facadesDir = $this->appPath('Facades');
    if(!file_exists($facadesDir)) mkdir($facadesDir);
    $kebabedAliasName = $this->kebabedAliasName();
    $classBaseName = $this->classBaseName();
    $aliasName = $this->aliasName;
    $filename = $facadesDir . '/' . $classBaseName . '.php';
    $fqnSvc = $this->target;
    $this->createPopulatedFile
    (
      $filename, 
      'facade', 
      compact('kebabedAliasName', 'classBaseName', 'fqnSvc')
    );
    return 'App\\Facades\\' . $classBaseName . '::class';
  }

  /**
   * Create a file under Providers/Facades dir.
   * @return  string  The created file relative to Providers dir.
   */
  private function genProvider():string
  {
    $file = 'Facades\\' . $this->aliasName;
    Artisan::call('make:provider', ['name' => $file]);
    return 'App\\Providers\\' . $file;
  }

  private function replaceProviderCnts()
  {
    $aliasName = $this->aliasName;
    $kebabedAliasName = $this->kebabedAliasName();
    $classBaseName = $this->classBaseName();
    $target = $this->target;
    $this->createPopulatedFile
    (
      $this->providerFullPath(), 
      'facade_service_provider', 
      compact('aliasName', 'kebabedAliasName', 'target')
    );
  }
  
  private function createPopulatedFile
  (
    string $path, 
    string $stub, 
    array $params
  )
  {
    $contents = view
    (
      "facade-generator::{$stub}", 
      $params
    )->render();
    file_put_contents($path, '<?php ' . $contents);
  }

  /**
   * Create a new command instance.
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   * @return mixed
   */
  public function handle()
  {
    $this->aliasName = $this->argument('alias-name');
    $this->target = $this->argument('target');
    $this->createProvider();
  }
}
