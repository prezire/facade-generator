namespace App\Facades;
use Illuminate\Support\Facades\Facade;
final class {{$classBaseName}} extends Facade
{
  /**
   * Get the registered name of the service.
   * @see  {{$fqnSvc}}
   */
  protected static function getFacadeAccessor()
  {
    return '{{$kebabedAliasName}}';
  }
}