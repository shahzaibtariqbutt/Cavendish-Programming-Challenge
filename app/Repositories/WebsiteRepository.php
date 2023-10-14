<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use App\Models\Vote;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WebsiteRepository
{
    public function websites(){

        if(user()){
            $websites = Website::where('status','approved')->withCount('votes')->with('categories')->orderBy('votes_count', 'desc')->get();
            $description = 'Showing all websites sorted by vote count.';
        }else{
            $websites = Website::with('categories')->where('status', 'approved')->get();
            $description = 'Showing categorized websites.';
        }
        return ['websites' => $websites, 'description' => $description];

    }

    public function search($request){

        $category = Category::with(['websites' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->where('id', $request->category_id)
        ->first();
        return $category;

    }

    public function store($request){

        $website = Website::create([
            'name' => $request->name,
            'user_id' => user()->id,
        ]);
        $website->categories()->sync($request->categories);
        return $website;

    }

    public function vote($request){

        $user = user();
        $vote = Vote::firstOrNew(['user_id' =>  $user->id, 'website_id' => $request->website_id]);
        if(request('vote_type') === '0'){
            $vote->delete();
        }else{
            $vote->vote_type = request('vote_type');
            $vote->save();
        }
        $voteType = request('vote_type') === "1" ? 'voted' : 'unvoted';
        return $voteType;
    }

    public function edit($request){

        $website = Website::find($request->website_id);
        if($website){
            $request->name ? $website->name = $request->name : '';
            $request->status ? $website->status = $request->status : '';
        }
        $website->update();
        $website->categories()->sync($request->categories);

    }

    public function destroy($request) {

        $website = Website::find($request->website_id);
        
        if($website){
            $website->delete();
            $message = 'success';
            $description = 'Website deleted successfully. Note: The website has not been completely removed from the database; it has been soft deleted.';
        }else{
            $message = 'warning';
            $description = 'Website ID required in order to delete it or it has been already deleted.';
        }
        return ['message' => $message, 'description' => $description];
    }
}