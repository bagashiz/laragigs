<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // index show all listings
    public function index()
    {
        return view(
            'listings.index',
            [
                'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
            ]
        );
    }

    // show single listing
    public function show(Listing $listing)
    {
        return view(
            'listings.show',
            [
                'listing' => $listing
            ]
        );
    }

    // create show a form to create a new listing
    public function create()
    {
        return view('listings.create');
    }

    // store save a new listing to the database
    public function store()
    {
        $formFields = request()->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if (request()->hasFile('logo')) {
            $formFields['logo'] = request()->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('success', 'Listing created successfully!');
    }

    // edit shows a form to edit an existing listing
    public function edit(Listing $listing)
    {
        return view(
            'listings.edit',
            [
                'listing' => $listing
            ]
        );
    }

    // update save the updated listing to the database
    public function update(Listing $listing)
    {
        // check if current user is the owner of the listing
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = request()->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => ['required', 'url'],
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if (request()->hasFile('logo')) {
            $formFields['logo'] = request()->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('success', 'Listing updated successfully!');
    }

    // destroy delete the listing from the database
    public function destroy(Listing $listing)
    {
        // check if current user is the owner of the listing
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();

        return redirect('/')->with('success', 'Listing deleted successfully!');
    }

    // manage show page to manage listings
    public function manage()
    {
        return view(
            'listings.manage',
            [
                'listings' => Listing::where('user_id', auth()->id())->latest()->paginate(6)
            ]
        );
    }
}
