<?php namespace Prezire\FacadeGenerator;

use Illuminate\Support\ServiceProvider;
use Prezire\FacadeGenerator\Console\Commands\FacadeGenerator;

final class FacadeServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   * @return void
   */
  public function register()
  {
    //Do nothing.
  }

  /**
   * Bootstrap any application services.
   * @return void
   */
  public function boot()
  {
    $this->loadViewsFrom(__DIR__.'/views', 'facade-generator');
    if($this->app->runningInConsole()) 
    {
      $this->commands([FacadeGenerator::class]);
    }
  }
}
