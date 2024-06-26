<?php

use Livewire\Volt\Component;
use Livewire\Attributes\{Validate, Url};
use App\Models\Todo;

new class extends Component {
    #[Validate('required:regex:^[\p{Emoji}]+$')]
    public string $text = '';

    #[Url]
    public $asc = false;

    public function submit()
    {
        $this->validate();

        Auth::user()->todos()->create($this->pull());
    }

    public function with()
    {
        $sortDirection = $this->asc ? 'asc' : 'desc';

        return [
            'todos' => Todo::with('user')->limit(20)->orderBy('id', $sortDirection)->get(),
        ];
    }
}; ?>

<main>
    <h1>
        Emoji TODO Livewire
        <a href="https://github.com/joshcirre/emoji-todo-livewire" target="_blank">
            source
        </a>
    </h1>
    <ul>
        @foreach ($todos as $todo)
            <li wire:key="todo-{{ $todo->id }}">
                <div class="flex items-center justify-between">
                    <p>{{ $todo->text }}</p>
                    <p>{{ $todo->user->name }}</p>
                </div>
            </li>
        @endforeach
    </ul>

    @auth
        <form wire:submit='submit'>
            <input wire:model='text' type="text" placeholder="🫡 (only emojis allowed)" pattern="^[\p{Emoji}]+$"
                name="text" autoFocus maxLength="10" required />
            <button>✉️</button>
        </form>
    @else
        <div class="flex items-center justify-center m-20">
            <a href="{{ route('login') }}"
                class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Log in to add a todo
            </a>
        </div>
    @endauth
</main>
