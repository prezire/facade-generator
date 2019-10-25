namespace App\Facades;
use Illuminate\Support\Facades\Facade;
final class {{$classBaseName}} extends Facade
{
  protected static function getFacadeAccessor()
  {
    return '{{$kebabedAliasName}}';
  }
}