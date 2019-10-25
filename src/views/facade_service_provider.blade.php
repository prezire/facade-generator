namespace App\Providers\Facades;
use Illuminate\Support\ServiceProvider;
final class {{$aliasName}} extends ServiceProvider
{
  /**
   * Register any application services.
   * @return void
   */
  public function register()
  {
    $this->app->bind('{{$kebabedAliasName}}', function(){
      return new {{$target}}();
    });
  }

  /**
   * Bootstrap any application services.
   * @return void
   */
  public function boot()
  {
    //
  }
}