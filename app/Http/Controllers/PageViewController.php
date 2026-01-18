<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * This class' purpose is to be the controller for standard page navigation
 */
class PageViewController extends Controller {

    /**
     * Show the home page.
     */
    public function home(): View {
       
        return view('home');
    }

    /**
     * Show the search page.
     */
    public function search(): View {
    
        return view('search');
    }

    /**
     * Show the signup page.
     */
    public function signup(): View {
    
        return view('auth.register');
    }

    /**
     * Show the submit sandwich page.
     */
    public function submit(): View {
        
        return view('submit');
    }

    /**
     * Show the user profile.
     */
    public function profile(): View {
        
        return view('profile');
    }

    /**
     * Show the change user password form.
     */
    public function changePassword(): View {
        
        return view('change-password');
    } 
}
