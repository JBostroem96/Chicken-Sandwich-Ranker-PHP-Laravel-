<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use App\Services\ChickenSandwichManager;
use App\Models\ChickenSandwich;

/**
 * This class' purpose is to perform different CRUD operations
 * for Chicken Sandwiches.
 */
class ChickenSandwichController extends Controller {

    private $chicken_sandwich;

    /**
     * Initialize a new Chicken Sandwich object
     */
    public function __construct() {

        $this->chicken_sandwich = new ChickenSandwich();
    }

    /**
     * Validate the images
     *
     * @param Request $request              the request object that contains the input
     */
    public function validateImages(Request $request) { 

        $sharedRulesForImages = ['required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048'];
            

        $request->validate([
                'image' => $sharedRulesForImages,
                'logo' => $sharedRulesForImages
        ]);
    }

    /**
     * Store the images
     *
     * @param Request $request              the request object that contains the input
     */
    public function storeImages(Request $request): Array {

        $images = [];

        $images[] = $request->file('image')->store('images', 'public');
        $images[] = $request->file('logo')->store('logos', 'public');
        
        return $images;
    }

    /**
     * Validate the data, then insert it, and then
     * redirect to the corresponding page
     *
     * @param Request $request              the request object that contains the input
     */
    public function store(Request $request): RedirectResponse {

        $request->validate([

            'name' => 'string|required',
            'company' => 'string|required',
        ]);

        //method call to validate the images
        $this->validateImages($request);

        try {

            $images = $this->storeImages($request);

            ChickenSandwich::create([

                'name' => $request['name'],
                'company' => $request['company'],
                'image' => $images[0],
                'logo' => $images[1]
            ]);
            
        } catch (Exception $e) {

            Log::error("Insert error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        return redirect()->route('chicken-sandwiches.index')->with('success', 'New chicken sandwich cooked up!');
    }

    /**
     * Display the ranked list of chicken sandwiches
     *
     * @param Request $request              the request object containing the search term and type
     */
    public function index(Request $request): View {

        $all_chicken_sandwiches = null;

        if ($request->input('min_score') || $request->input('max_score')) {

            $request->validate([

                'min_score' => 'nullable|numeric',
                'max_score' => 'nullable|numeric',
            ]);

            $min_score = $request->input('min_score');
            $max_score = $request->input('max_score');

            $all_chicken_sandwiches = ChickenSandwich::whereBetween($request->input('search-type'), [$min_score, $max_score])->get();

        } elseif ($request->input('search-type') === 'name' && !$request->has('view-all')) {

            $all_chicken_sandwiches = ChickenSandwich::where('name', $request->input('name'))->get();

        } else {

            $all_chicken_sandwiches = ChickenSandwich::all();
        }
            
        $ratings = [];

        //if the user is signed in, assign the logged in user to this user variable
        if ($user = auth()->user()) {

            //get all the chicken sandwiches by the user logged in
            $user_ratings = $user->chickenSandwiches()->get();

            //loop through these entries and assign the IDs to the ratings
            foreach ($user_ratings as $entry) {

                $ratings[$entry->id] = $entry->pivot;
            }
        }

        return view('results', compact('all_chicken_sandwiches', 'ratings'));
    }

    /**
     * Validate the data and then delete it.
     * It then redirects with the appropriate message.
     *
     * @param Request $request              the request object containing the chicken sandwich entry to be deleted
     * @param int $id               the entry to be deleted
     */
    public function destroy($id): RedirectResponse {

        try {

            $chicken_sandwich = ChickenSandwich::findOrFail($id);
            
            $chicken_sandwich->delete();
            
            return redirect()->route('chicken-sandwiches.index')->with('success', 'Deleted!');
        
        } catch (\Exception $e) {

            \Log::error("Delete error: " . $e->getMessage());
            return redirect()->route('chicken-sandwiches.index')->with('error', 'Could not delete.');
        }
    }

    /**
     * Validate the input, update said entry, and then
     * redirect with the appropriate message
     *
     * @param Request $request              the request object containing the input
     */
    public function update(Request $request): RedirectResponse {

        $request->validate([

            'chicken-sandwich-update' => 'required|integer|exists:chicken_sandwiches,id',
        ]);

        $chicken_sandwich_id = $request->input('chicken-sandwich-update');

        $chicken_sandwich = ChickenSandwich::findOrFail($chicken_sandwich_id);
         
        //method call to validate the images
        $this->validateImages($request);

        //validate the input
        $validation = $request->validate([

            //starting with 'name'
            'name' => [
                'required',
                'string',
                //unique rule to ensure that the company isn't the same
                Rule::unique('chicken_sandwiches')
                    //ensure the chicken sandwich name uniqueness for the same company
                    ->where(function ($query) use ($request) {
                        return $query->where('company', $request->input('company'));
                    })
                    //ignores the current row when determining uniqueness
                    ->ignore($chicken_sandwich_id),
            ],
            'company' => ['required', 'string'], 
        ]);

        try {

            $images = $this->storeImages($request);

            $chicken_sandwich->update([
                'name' => $validation['name'],
                'company' => $validation['company'],
                'image' => $images[0],
                'logo' => $images[1]
            ]);


        } catch (Exception $e) {

            \Log::error("Update error: " . $e->getMessage());

            return redirect()->back()->with('error', 'Entry failed to update');
        }  

        return redirect()->back()->with('success', 'Entry updated!');
    }
    

    /**
     * Return the view of the entry that is being edited
     *　
     * @param int $chicken_sandwich_id                  the chicken sandwich to be edited
     */
    public function edit($chicken_sandwich_id): View {

        $chicken_sandwich = ChickenSandwich::findOrFail($chicken_sandwich_id);
        return view('submit', compact('chicken_sandwich'));
    }
}
