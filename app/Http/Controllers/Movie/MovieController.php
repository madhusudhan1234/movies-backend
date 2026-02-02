<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\BaseController;
use App\Http\Filters\MovieFilter;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Http\Transformers\MovieTransformer;
use App\Repositories\Movie\MovieRepository;
use App\Services\Movie\MovieServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JoBins\LaravelRepository\Exceptions\LaravelRepositoryException;
use Symfony\Component\HttpFoundation\Response;

class MovieController extends BaseController
{
    public function __construct(
        private readonly MovieServiceInterface $movieService,
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

        return $this->success('', [
            'data'       => $movies,
            'pagination' => $meta['pagination'],
        ]);
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        try {
            $movie = $this->movieService->createMovie($request->validated());

            return $this->success(
                'Movie created successfully.',
                new MovieResource($movie),
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return $this->error('Unable to create movie.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->movieService->getMovieById($id);

        return $this->success('Movie retrieved successfully.', new MovieResource($movie));
    }

    public function update(UpdateMovieRequest $request, int $id): JsonResponse
    {
        $updatedMovie = $this->movieService->updateMovie($id, $request->validated());

        return $this->success('Movie updated successfully.', new MovieResource($updatedMovie));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->movieService->deleteMovie($id);

        return $this->success('Movie deleted successfully.');
    }
}
