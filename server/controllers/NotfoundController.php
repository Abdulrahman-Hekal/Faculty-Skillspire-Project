<?php

class NotfoundController extends Controller
{
    public function index()
    {
        $this->requireView('not-found/not-found', ['title' => 'Page Not Found']);
    }
}
