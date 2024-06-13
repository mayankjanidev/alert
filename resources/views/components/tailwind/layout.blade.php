<div role="alert" {{ $attributes->class(['flex items-center justify-between p-3 rounded-md']) }}>
    <div class="flex items-center">
        @if($icon)
        <div class="mr-2" aria-hidden="true">
            {{ $icon }}
        </div>
        @endif
        <div>
            <div class="text-lg font-medium">{{ $title }}</div>
            @if($description)
            <div @class(['font-medium'=> !$title])>{{ $description }}</div>
            @endif
        </div>
    </div>
    <div class="cursor-pointer" onclick="parentNode.remove()" aria-label="Close">
        <svg @class([ 'w-8 h-8 fill-current'=> $title,
            'w-5 h-5 fill-current' => !$title]) xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M0 0h24v24H0z" fill="none" />
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
        </svg>
        <div class="sr-only text-sm">Close</div>
    </div>
</div>