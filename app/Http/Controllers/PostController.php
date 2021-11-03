<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Newsletter;

class PostController extends Controller
{
    public function index()
    {
        $featured = Article::where('featured', 'yes')->where('status', 'Published')->first();
        $articles = Article::where('featured', 'no')->where('status', 'Published')->paginate(4);
        return view('welcome', compact('featured', 'articles'));
    }

    public function singlePost($slug)
    {
        $article = Article::where('slug', $slug)->first();
        $title = $article->title;
        $others = Article::where('id', '!=', $article->id)->get();
        $keywords = $article->keywords;
        $descriptions = $article->description;

        //social media share
        $shareComponent = \Share::page(
            route('single', $article->slug),
            $article->title,
        )
        ->facebook()
        ->twitter()
        ->linkedin()
        ->whatsapp()        
        ->reddit();
        return view('single-post', compact('title', 'article', 'others', 'keywords', 'descriptions', 'shareComponent'));
    }

    public function postsBycategory($cat)
    {
        $category = str_replace('-', ' ', $cat);
        $title = $category;
        $articles = Article::where('category', $category)->paginate(5);
        return view('by-category', compact('title', 'articles', 'category'));
    }

    public function saveEmail()
    {
        $newsletter = new Newsletter;
        $newsletter->email = request()->email;
        $newsletter->save();
        return true;
    }
}
