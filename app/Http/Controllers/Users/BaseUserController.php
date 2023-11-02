<?
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Service\UserService;

class BaseUserController extends Controller
{
    protected $service;
    function __construct(UserService $service)
    {
        $this->service = $service;
    }
}
