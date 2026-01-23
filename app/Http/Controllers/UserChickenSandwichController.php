<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChickenSandwich;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\UserChickenSandwich;
use App\Http\Controllers\ChickenSandwichController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 * This class' purpose is to perform CRUD operations on Chicken Sandwich entries
 */
class UserChickenSandwichController extends Controller {

    private $chicken_sandwich;

    public function __construct() {

        $this->chicken_sandwich = new ChickenSandwich();
    }

    /**
     * return the logged in user id
     *
     */
    public function getLoggedInUserId(): int {

        return auth()->id(); 
    }

   /**
    * Validates and inserts the new score, and then updates the score
    *
    * @param Request $request                   the request object containing the input                 
    */
   public function store(Request $request): RedirectResponse {

        $validated = $request->validate([
                'score' => 'required|integer|min:1|max:10',
                'chicken_sandwich_id' => 'required|integer|exists:chicken_sandwiches,id',
                'review' => 'nullable|string|max:1000',
        ]);
            
        $chicken_sandwich = ChickenSandwich::findOrFail($validated['chicken_sandwich_id']);
        
        $user_id = $this->getLoggedInUserId();

        // Check if the user already has a review
        $existing_entry = UserChickenSandwich::where('user_id', $user_id)
            ->where('chicken_sandwich_id', $chicken_sandwich->id)
            ->first();

        if ($existing_entry) {

            // User already has a review; return message
            return redirect()
                ->route('chicken-sandwiches.index')
                ->with('error', 'You have already submitted a review for this sandwich.');
        }

        try {
            // Create new review
            UserChickenSandwich::create([
                'user_id' => $user_id,
                'chicken_sandwich_id' => $chicken_sandwich->id,
                'score' => $validated['score'],
                'review' => $validated['review'] ?? null,
            ]);

            // Update sandwich totals only for new reviews
            $chicken_sandwich->updateScore($validated);

        } catch (\Exception $e) {

            \Log::error("Submit error: " . $e->getMessage());

            return redirect()
                ->route('chicken-sandwiches.index')
                ->with('error', 'Failed to submit rating.');
        }

        return redirect()
                ->route('chicken-sandwiches.index')
                ->with('success', 'Rating submitted!');
    }

    /**
     * Display a list of the user's chicken sandwich ratings.
     */
    public function index(): View {
        
        $ratings = $this->fetchRatings();

        return view('profile_ratings', compact('ratings'));
    }

    /**
     * Show the form for editing the user's rating for this entry
     * @param int $id               the entry to be edited
     */
    public function edit($id): View {

        $rating = $this->fetchUserChickenSandwich($id);

        return view('edit_rating', compact('rating'));
    }

    /** 
     * Fetches all ratings for the authenticated user.
     */ 
    public function fetchRatings(): Collection {

        $ratings = auth()->user()
        ->chickenSandwiches()
        ->withPivot(['score', 'review'])
        ->get();

        return $ratings;
    }

    /**
     * Update the chicken sandwich rating
     *
     * @param  Request  $request                the request object containing the input
     * @param  int  $id             the entry to be updated
     */
    public function update(Request $request, $id): RedirectResponse {

        $validated = $request->validate([
            'new_score' => 'required|integer|min:1|max:10',
            'review' => 'nullable|string|min:30|max:1000',
        ]);

        try {

            $user_chicken_sandwich = $this->fetchUserChickenSandwich($id);

            $old_score = $user_chicken_sandwich->score;
            $user_chicken_sandwich->update([ 'score' => $validated['new_score'], 'review' => $validated['review']]);

            $this->chicken_sandwich = ChickenSandwich::findOrFail($id);

            // Update the sandwich's score based on the new rating
            $this->chicken_sandwich->updateScoreOnEdit($validated, $old_score);
  
            return redirect()
                ->route('profile.ratings.index')
                ->with('success', 'Rating updated!');

        } catch (\Exception $e) {

            \Log::error("Update error: " . $e->getMessage());

            return redirect()
                ->route('profile.ratings.index')
                ->with('error', 'Failed to update rating.');
        }
    }

    /**
     *  retrieve the chicken sandwich by user and entry id
     */
    private function fetchUserChickenSandwich($id): UserChickenSandwich {

        $userId = $this->getLoggedInUserId();

        //SQL Query to fetch the entry
        $entry = UserChickenSandwich::where('user_id', $userId)
            ->where('chicken_sandwich_id', $id)
            ->firstOrFail();

        return $entry;
    }
    
    /**
     * Verify that the entry belongs to the user and then delete it by id
     *
     * @param int $id               the entry to be deleted
     */
    public function destroy($id): RedirectResponse {

        try {

            $entry = $this->fetchUserChickenSandwich($id);

            $old_score = $entry->score;
            $entry->delete();

            $this->chicken_sandwich = ChickenSandwich::findOrFail($id);

            // Update the sandwich's score based on the deleted rating
            $this->chicken_sandwich->updateScoreOnDelete($old_score);
  
            return redirect()
                ->route('profile.ratings.index')
                ->with('success', 'Rating deleted!');

        } catch (\Exception $e) {

            \Log::error("Delete error: " . $e->getMessage());

            return redirect()
                ->route('profile.ratings.index')
                ->with('error', 'Failed to delete rating.');
        }
    }
}
