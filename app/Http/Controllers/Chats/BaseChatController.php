<?
namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Service\ChatService;

class BaseChatController extends Controller
{
    protected $service;
    function __construct(ChatService $service)
    {
        $this->service = $service;
    }
}
