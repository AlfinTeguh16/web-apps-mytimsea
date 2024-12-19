@extends('master')

@section('content')

<section class="mx-3">


<div class="flex justify-end mx-3">
    <div>
        <a href="{{ route('create.articles') }}" class="bg-blue-600 hover:bg-blue-700 py-3 px-3.5 rounded-xl text-white flex items-center justify-center">
            <i class="ph ph-file-plus text-2xl"></i>
        </a>
    </div>
</div>

@forelse ($getArticles as $article)
    <div class="p-3 border border-gray-100 rounded-xl m-3 flex flex-row justify-between w-fit hover:shadow-sm">
        <div class="flex justify-center flex-col">
            <h2 class="font-semibold">{{ $article->title }}</h2>
            @if ($article->banner)
                <img src="{{ asset($article->banner) }}" alt="{{ $article->title }}" class="max-w-72 xl:max-w-[400px] xl:max-h-[400px] mb-3 rounded-lg">
            @else
                <p><i>empty banner</i></p>
            @endif
            <div>
                <span 
                    @class([
                        'bg-orange-500' => $article->status === 'pending',
                        'bg-green-500' => $article->status === 'approved',
                        'bg-red-400' => $article->status === 'rejected',
                        'text-white px-2 py-1 rounded'
                    ])>
                    {{ $article->status }}
                </span>
            </div>
            <p>Writer: <strong>{{ $article->user->username }}</strong></p>
            <p class="text-gray-500"><small>{{ $article->created_at->format('d M Y, H:i') }}</small></p>
        </div>

    </div>
@empty
    <div class="flex justify-center items-center h-screen">
        <p>Looks like you does't make articles yet</p>
    </div>
@endforelse
</section>
@endsection