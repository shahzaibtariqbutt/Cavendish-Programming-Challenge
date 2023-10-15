<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vote;
use App\Models\Website;
use App\Repositories\WebsiteRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    protected $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function websites()
    {
        $data = $this->websiteRepository->websites();

        return response()->json([
            'message' => 'success',
            'description' => $data['description'],
            'websites' => $data['websites'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required'],
        ]);

        $category = $this->websiteRepository->search($request);

        return response()->json([
            'message' => 'success',
            'description' => 'Showing all approved websites (by admin) under the ' .$category->name. ' category.',
            'categories' => $category->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required']
        ]);
        $website = $this->websiteRepository->store($request);
        return response()->json([
            'message' => 'success',
            'description' => 'website name '. $website->name. ' stored successfully, and will be published once approved by the admin.',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function vote(Request $request)
    {
        $validated = $request->validate([
            'vote_type' => ['required'],
            'website_id' => ['required'],
        ]);

        $voteType = $this->websiteRepository->vote($request);
        return response()->json([
            'message' => 'success',
            'description' => 'You have successfully '. $voteType . ' to the website.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function admin_websites(Request $request)
    {
        $websites = Website::with('categories')->when($request->status, fn ($query) => $query->where('status', $request->status))->get();
        return response()->json([
            'message' => 'success',
            'description' => 'showing all websites along with their statuses.',
            'websites' => $websites
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit(Request $request)
    {

        $validated = $request->validate([
            'website_id' => ['required'],
        ]);
        $this->websiteRepository->edit($request);
        return response()->json([
            'message' => 'success',
            'description' => 'Website edited successfully.'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = $this->websiteRepository->destroy($request);
        
        return response()->json([
            'message' => $data['message'],
            'description' => $data['description']
        ]);
    }
}
