<?php

declare(strict_types=1);

namespace App\Http\Controllers\Movie;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Services\Movie\MovieServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MovieController extends Controller
{
    public function __construct(
        private readonly MovieServiceInterface $movieService
    ) {}

    public function index(Request $request)
    {
        $query = $request->query('q') ?? '';

        $movies = $this->movieService->getPaginatedMovies(15, $query);

        return response()->json([
            'data' => MovieResource::collection($movies),
            'pagination' => [
                'total' => $movies->total(),
                'per_page' => $movies->perPage(),
                'current_page' => $movies->currentPage(),
                'last_page' => $movies->lastPage(),
            ],
        ]);
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        try {
            $movie = $this->movieService->createMovie($request->validated());

            return response()->json([
                'message' => 'Movie created successfully',
                'data' => new MovieResource($movie),
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to create movie',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        $movie = $this->movieService->getMovieById($id);

        return response()->json([
            'data' => new MovieResource($movie),
        ]);
    }

    public function update(UpdateMovieRequest $request, int $id): JsonResponse
    {
        $updatedMovie = $this->movieService->updateMovie($id, $request->validated());

        return response()->json([
            'message' => 'Movie updated successfully',
            'data' => new MovieResource($updatedMovie),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->movieService->deleteMovie($id);

        return response()->json([
            'message' => 'Movie deleted successfully',
        ]);
    }
}
