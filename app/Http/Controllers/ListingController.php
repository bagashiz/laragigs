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
}
