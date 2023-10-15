<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\VoteRequest;
use App\Http\Requests\WebsiteDestroyRequest;
use App\Http\Requests\WebsiteRequest;
use App\Models\Website;
use App\Repositories\WebsiteRepository;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    protected $websiteRepository;

    public function __construct(WebsiteRepository $websiteRepository)
    {
        $this->websiteRepository = $websiteRepository;
    }
    /**
     * Display a listing of the websites.
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
     * Search the websites category wise.
     */
    public function search(SearchRequest $request)
    {
        $category = $this->websiteRepository->search($request);

        return response()->json([
            'message' => 'success',
            'description' => 'Showing all approved websites under these categories.',
            'categories' => $category->toArray(),
        ]);
    }

    /**
     * Store a newly created website in storage.
     */
    public function store(WebsiteRequest $request)
    {
        $website = $this->websiteRepository->store($request);
        return response()->json([
            'message' => 'success',
            'description' => 'website name '. $website->name. ' stored successfully, and will be published once approved by the admin.',
        ]);

    }

    /**
     * Vote the specific website.
     */
    public function vote(VoteRequest $request)
    {
        $voteType = $this->websiteRepository->vote($request);
        return response()->json([
            'message' => 'success',
            'description' => 'You have successfully '. $voteType . ' to the website.'
        ]);
    }

    /**
     * Show the data to just admin.
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
    public function edit(WebsiteDestroyRequest $request)
    {
        $this->websiteRepository->edit($request);
        return response()->json([
            'message' => 'success',
            'description' => 'Website updated successfully. It will show in the directory if you approved it.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebsiteDestroyRequest $request)
    {
        $data = $this->websiteRepository->destroy($request);
        
        return response()->json([
            'message' => $data['message'],
            'description' => $data['description']
        ]);
    }
}
