<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gamelist;
use Spatie\Tags\Tag;

class GameController extends Controller
{
    public function getGames(Request $request)
    {
        $type = $request->query('type');
        $page = (int) $request->query('page', 1);
        $pageSize = (int) $request->query('pageSize', 10);
        $providers = $request->query('provider', []);
        $searchQuery = $request->query('search', '');
        $tags = $request->query('tags', []);
        $popular = $request->query('popular'); // New filter
        $new = $request->query('new'); // New filter
        $freerounds_supported = $request->query('freerounds_supported'); // New filter

        try {
            if ($type === 'providers') {
                $providers = Gamelist::whereNotNull('category')
                    ->where('active', true)
                    ->distinct()
                    ->pluck('category')
                    ->toArray();

                // Sort providers alphabetically
                sort($providers);

                return response()->json(['providers' => $providers]);
            } else {
                $query = Gamelist::query()->where('active', true)->orderBy('index_rating', 'ASC');

                // Filter by providers
                if (!empty($providers)) {
                    $providersArray = explode(',', $providers);
                    $query->whereIn('category', $providersArray);
                }

                // Search by name (case-insensitive)
                if ($searchQuery) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchQuery) . '%']);
                }

                // Filter by tags
                if (!empty($tags)) {
                    $query->withAnyTags($tags);
                }

                // Filter by popular games
                if ($popular) {
                    $query->where('popular', true);
                }

                // Filter by new games
                if ($new) {
                    $query->where('new', true);
                }

                // Filter by feature buy-in games
                if ($freerounds_supported) {
                    $query->where('freerounds_supported', true);
                }

                $totalGames = $query->count();
                $totalPages = ceil($totalGames / $pageSize);
                $games = $query->skip(($page - 1) * $pageSize)
                    ->take($pageSize)
                    ->get();

                $result = [
                    'page' => $page,
                    'pageSize' => $pageSize,
                    'totalPages' => $totalPages,
                    'totalGames' => $totalGames,
                    'games' => $games,
                ];

                return response()->json($result);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getGameDetails($provider, $gameSlug)
    {
        $idHash = $provider . '/' . $gameSlug;
        $game = Gamelist::where('id_hash', $idHash)->firstOrFail();

        // exlude id, created at, updated at, active
        $game->makeHidden(['id', 'created_at', 'updated_at', 'active']);
        return response()->json($game);
    }
}
