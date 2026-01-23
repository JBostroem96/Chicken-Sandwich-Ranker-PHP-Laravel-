<?php

namespace App\Models;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * UserChickenSandwich model representing the pivot table between users and chicken sandwiches.
 * Basic for now, but might add more to it depending on requirements.
 */
class UserChickenSandwich extends Pivot {

    protected $table = 'user_chicken_sandwiches';

    protected $fillable = [
        'id',
        'user_id',
        'chicken_sandwich_id',
        'score',
        'review',
    ];

    /**
     * Get the score associated with this pivot record.
     */
    public function getScore(): int {

        return $this->score;
    }

    /**
     * Retrieve all chicken sandwiches ordered by average rating (descending).
     */
    public function readAll(): Collection {

        //this returns all chicken sandwiches ordered by average in desc order
        return ChickenSandwich::orderBy('average_score', 'desc')->get();
    }

    /**
     * Search chicken sandwiches by a specific column and value
     *
     * @param string $search_type               the column to search by (e.g., 'name' or 'average')
     * @param string|int $search_term           the search value to match against
     * @throws \InvalidArgumentException If the search type is not allowed
     */
    public function readBySearchType($search_type, $search_term): ChickenSandwich {

        //only allow these columns
        $allowed_columns = ['name', 'average'];

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
     * Update the score on score edit
     *
     * @param array $validated              the validated input containing the scores
     */
    public function updateUserScoreOnEdit($validated): void {

        $this->score = $this->score - $validated['old_score'] + $validated['new_score'];

        // save changes
        $this->save();
    }
}
