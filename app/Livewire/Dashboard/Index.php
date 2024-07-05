<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Index extends Component
{

    public $title = 'Dashboard-home';

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
