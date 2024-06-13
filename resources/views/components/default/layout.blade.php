<div role="alert" {{ $attributes->merge(['style' => 'display: flex;align-items: center;justify-content: space-between;padding: 0.75rem;border-radius: 0.375rem;']) }}>
    <div style="display: flex;align-items:center">
        @if($icon)
        <div style="margin-right: 0.5rem;" aria-hidden="true">
            {{ $icon }}
        </div>
        @endif
        <div>
            @if($title)
            <div style="font-size: 1.125rem;line-height: 1.75rem;font-weight: 500">{{ $title }}</div>
            @endif
            <div @style(['font-weight: 500'=> !$title])>{{ $description }}</div>
        </div>
    </div>
    <div style="cursor:pointer" onclick="parentNode.remove()" aria-label="Close">
        <svg @style(['fill: currentColor', 'width: 2rem;height: 2rem;'=> $title, 'width: 1.25rem;height: 1.25rem;'=> !$title]) xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M0 0h24v24H0z" fill="none" />
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
        </svg>
        <div style="font-size: 0.875rem;line-height: 1.25rem;position: absolute;width: 1px;height: 1px;padding: 0;margin: -1px;overflow: hidden;clip: rect(0, 0, 0, 0);white-space: nowrap;border-width: 0;">Close</div>
    </div>
</div>