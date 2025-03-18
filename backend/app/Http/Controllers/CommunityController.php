<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommunityRequest;
use App\Http\Requests\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CommunityController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum',except:['index','show'])
        ];
    }

    public function index()
    {
        $allCommunities = Community::paginate(15);
        return response()->json($allCommunities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommunityRequest $request)
    {
        $data = $request->validated();
        $community = $request->user()->communities()->create($data);
        return response()->json($community);
    }

    /**
     * Display the specified resource.
     */
    public function show(Community $community)
    {
        return response()->json($community);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommunityRequest $request, Community $community)
    {
        $data = $request->validated();
        if($community['user_id'] === $request->user()->id){
            $community->update($data);
            return response()->json(Community::find($community['id']));
        }else{
            return response([
                'message' => 'You are not allowed to update this community'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Community $community)
    {
        $community->delete();
        return response(['message' => 'Community deleted']);
    }
}
