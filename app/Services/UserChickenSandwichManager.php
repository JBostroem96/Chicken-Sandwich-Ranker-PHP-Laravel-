<?php

namespace App\Services;

use App\Models\ChickenSandwich;
use Illuminate\Database\Eloquent\Collection;

/**
 * This class' purpose is to perform DB related inquiries for Chicken Sandwiches
 */
class UserChickenSandwichManager {

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
     * @param string $search_type                   the column to search by (e.g., 'name' or 'average').
     * @param string|int $search_term               the search value to match against.
     *
     * @throws \InvalidArgumentException            If the search type is not allowed
     */
    public function readBySearchType($search_type, $search_term): Collection {

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
}
?>
