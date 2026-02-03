<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Http\Filters\MovieFilter;
use App\Http\Transformers\MovieTransformer;
use App\Models\User;
use App\Repositories\Movie\MovieRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 *
 */
class FavoritesController extends Controller
{
    public function __construct(
        protected readonly UserRepository $userRepository,
        protected readonly MovieRepository $movieRepository,
    ) {
    }

    public function getListOfFavorites(Request $request): JsonResponse
    {
        $queries             = $request->validate([
            'q'        => 'nullable|string',
            'per_page' => 'nullable|integer',
        ]);
        $perPage             = $request->input('per_page', 10);
        $queries['favorite'] = $request->user()->id;

        $this->movieRepository->with([
            'genres',
            'directors',
            'producers',
            'writers',
            'producers',
        ]);
        $this->movieRepository->filter(new MovieFilter($queries));
        $this->movieRepository->setTransformer(new MovieTransformer());
        $movies = $this->movieRepository->paginate($perPage);
        $meta   = array_pop($movies);

        return $this->success($movies, metadata: $meta['pagination']);
    }

    /**
     * @param int $movieId
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function addToFavorites($movieId): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->userRepository->toggleFavorites((int) $movieId, $user, true);

        return $this->success(message: 'Movie added to favorites.');
    }

    /**
     * @param $movieId
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function removeFromFavorites($movieId): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->userRepository->toggleFavorites((int) $movieId, $user, false);

        return $this->success(message: 'Movie removed from favorites.');
    }
}
