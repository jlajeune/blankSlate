<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }



    public function index()
    {
        //
        $recipes = \DB::table('Recipes')->get();
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $this->validate(request(), [
            'RecipeName' => 'required',
            'directions' => 'required'
        ]);


        //create a new meal using request data (from create function/page)
        $recipe = new \App\Recipe;
        $recipe->name = Request('RecipeName');
        $recipe->directions = Request('directions');
        $recipe->owner_UserID = auth()->id();
        //save it to the database
        


        $file = $request->file('recipeImg');
        $filename = Request('RecipeName') . auth()->id() . '.jpg';
        if ($file) {
            Storage::disk('uploads')->put($filename, File::get($file));
            $recipe->filename = Request('RecipeName') . auth()->id() . '.jpg';
        }

        $recipe->save();

        //and then redirect to the home page
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $recipe = \DB::table('Recipes')->find($id);
        $ingredients =\DB::table('RecipeIngredients')->where('recipeID', $id)->get();

        //return $recipe;
        return view('recipes/show', compact('recipe', 'ingredients'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
