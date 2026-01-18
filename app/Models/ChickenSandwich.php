<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;

/**
 * ChickenSandwich model representing chicken sandwich entries
 */
class ChickenSandwich extends Model {

    protected $fillable = [
        'id',
        'name',
        'company',
        'score',
        'image',
        'logo',
        'average_score',
        'entries',
    ];

    /**
     * Eloquent model, returns user chicken sandwiches and pivots score and review
     */
    public function users(): BelongsToMany {

        return $this->belongsToMany(User::class, 'user_chicken_sandwiches')
                    ->using(UserChickenSandwich::class)
                    ->withPivot(['score', 'review'])
                    ->withTimestamps();
    }

    /*  Getter methods */
    public function getName(): string {

        return $this->name;
    }

    public function getId(): int {

        return $this->id;
    }

    public function getCompany(): string {

        return $this->company;
    }

    /**
     * Update the score statistics for the chicken sandwich
     *
     * @param array $validated Validated input containing 'score'
     */
    public function updateScore($validated): void {

         // increment entries
        $this->entries = $this->entries + 1;

        // add new score to total score
        $this->score = $this->score += $validated['score'];

        // recalc average
        $this->average_score = $this->score / $this->entries;

        // save changes
        $this->save();
    }

    /**
     * Update the chicken sandwich score upon deletion
     *
     * @param int score          the score that has been passed over
     */
    public function updateScoreOnDelete($score): void {

        $this->entries = $this->entries - 1;

        
        $this->score = $this->score - $score;

        if ($this->entries > 0) {

            $this->average_score = $this->score / $this->entries;

        } else {

            $this->average_score = 0;
        }

        $this->save();
    }

    /**
     * Update the chicken sandwich score upon editing the review
     *
     * @param array $validated            the validated data
     * @param int $old_score          the old score that has been passed over
     */
    public function updateScoreOnEdit($validated, $old_score): void {

        $this->score = $this->score - $old_score + $validated['new_score'];

        if ($this->entries > 0) {

            $this->average_score = $this->score / $this->entries;

        } else {

            $this->average_score = 0;
        }

        $this->save();
    }

    /**
     * Return all chicken sandwiches
     */
    public function readAll(): Collection {

        //this returns all chicken sandwiches ordered by average in desc order
        return ChickenSandwich::orderBy('average_score', 'desc')->get();
    }

    /**
     * Search chicken sandwiches by a specific column and value
     *
     * @param string $search_type                   The column to search by (e.g., 'name' or 'average').
     * @param string|int $search_term               The search value to match against.
     *
     * @throws \InvalidArgumentException if the search type is not allowed
     */
    public function readBySearchType($search_type, $search_term): Collection {

        //only allow these columns
        $allowed_columns = ['name', 'average', 'score'];

        //if these are not present, throw an error
        if (!in_array($search_type, $allowed_columns)) {
            
            throw new \InvalidArgumentException("Invalid search type.");
        }

        /**
         * return entries where the search type exists, and the search term equals the input by the user,
         * then order it by the average in descending order
         */
        return ChickenSandwich::where($search_type, 'LIKE', '%' . $search_term . '%')
            ->orderBy('average_score', 'desc')
            ->get();
    }

    /**
     * Update an existing chicken sandwich entry
     *
     * @param int $chicken_sandwich_id                  the id of the chicken sandwich to update.
     * @param array $validation                 validated input containing 'name' and 'company'.
     */
    public function updateEntry($chicken_sandwich_id, $validation): RedirectResponse {

        try {
            
            $chicken_sandwich = ChickenSandwich::findOrFail($chicken_sandwich_id);

            $chicken_sandwich->update([
                'name' => $validation['name'],
                'company' => $validation['company'],
            ]);

        } catch (\Exception $e) {

            \Log::error("Update error: " . $e->getMessage());

            return redirect()->route('chicken-sandwiches.edit', $chicken_sandwich_id)
                ->with('error', 'Something went wrong.');
        }

        return redirect()->route('chicken-sandwiches.edit', $chicken_sandwich_id)
                ->with('success', 'Chicken sandwich edited!'); 
    }
}
