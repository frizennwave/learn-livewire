<?php

use App\Models\Subscriber;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|email|unique:subscribers,email')]
    public $email = '';

    public function subscribe()
    {
        $this->validate();

        $subscriber = new Subscriber([
            'email' => $this->email,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $subscriber->save();

        session()->flash('subscribe-success', 'Thanks for subscribing! You\'ll receive notifications about new posts');

        $this->email = '';
    }

    public function render()
    {
        return $this->view();
    }
};
