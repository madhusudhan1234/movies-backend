<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\BaseController;
use App\Http\Filters\MovieFilter;
use App\Http\Transformers\MovieTransformer;
use App\Repositories\Movie\MovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;

/**
 *
 */
class MovieController extends BaseController
{
    public function __construct(
        protected readonly MovieRepository $movieRepository
    ) {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws LaravelRepositoryException
     */
    public function index(Request $request): JsonResponse
    {
        $queries = $request->validate([
            'q'        => 'nullable|string',
            'per_page' => 'nullable|integer',
        ]);
        $perPage = $request->input('per_page', 10);

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
     * @throws LaravelRepositoryException
     */
    public function show($movieId): JsonResponse
    {
        $this->movieRepository->with([
            'genres',
            'directors',
            'producers',
            'writers',
            'producers',
        ]);
        $this->movieRepository->setTransformer(new MovieTransformer());
        $movie = $this->movieRepository->find((int) $movieId);

        return $this->success($movie);
    }
}
