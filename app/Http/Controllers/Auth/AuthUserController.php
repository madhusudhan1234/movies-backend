<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Helper;
use App\Http\Actions\UserRegisterAction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Transformers\UserTransformer;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;

/**
 *
 */
class AuthUserController extends BaseController
{
    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
    }

    /**
     * @param RegisterRequest    $request
     * @param UserRegisterAction $action
     *
     * @return JsonResponse
     * @throws LaravelRepositoryException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $userProfile = Helper::transform($request->user(), new UserTransformer());

        return $this->success($userProfile);
    }
}
