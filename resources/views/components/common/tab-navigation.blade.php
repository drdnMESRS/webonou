{{--
    resources/views/components/flowbite-tabs.blade.php

    This Blade component creates a reusable tab interface using Flowbite styles
    and Alpine.js for interactive tab switching.

    Props:
    - $tabs: An array of tab data. Each tab object/associative array should
             have 'id', 'title', and 'content'.
             'content' can now be:
             1. A string containing raw HTML.
             2. An array where each item is:
                - A string containing raw HTML.
                - An associative array like ['view' => 'path.to.blade.view', 'data' => [...]]
                  to include a Blade view with optional data.

             Example structures for 'content':
             - 'content' => '<div>Simple text or HTML string</div>'
             - 'content' => [
                 '<p>Paragraph 1</p>',
                 '<ul><li>List Item</li></ul>'
               ]
             - 'content' => [
                 ['view' => 'partials.my-table', 'data' => ['users' => $someUsers]],
                 '<p>Some text after the included view.</p>'
               ]
             - 'content' => ['view' => 'full-page-content'] // Single include
    - $initialTabId (optional): The ID of the tab to be active by default.
                                If not provided, the first tab will be active.
--}}

@props(['tabs', 'initialTabId' => null])

{{-- Check if tabs array is provided and not empty --}}
@if (empty($tabs))
    <div class="text-center text-gray-500 p-4">
        No tabs data provided for this component.
    </div>
@else
    {{-- Alpine.js data for managing active tab state --}}
    <div x-data="{ activeTab: '{{ $initialTabId ?: ($tabs[0]['id'] ?? null) }}' }" class="w-full">
        {{-- Tab Navigation --}}
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                @foreach ($tabs as $tab)
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg
                                   hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            :class="{
                                'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500 active': activeTab === '{{ $tab['id'] }}',
                                'border-transparent': activeTab !== '{{ $tab['id'] }}'
                            }"
                            id="{{ $tab['id'] }}-tab"
                            type="button"
                            role="tab"
                            aria-controls="{{ $tab['id'] }}"
                            aria-selected="activeTab === '{{ $tab['id'] }}'"
                            @click="activeTab = '{{ $tab['id'] }}'"
                        >
                            {{ $tab['title'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Tab Content --}}
        <div id="default-tab-content">
            @foreach ($tabs as $tab)
                <div
                    class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800"
                    id="{{ $tab['id'] }}"
                    role="tabpanel"
                    aria-labelledby="{{ $tab['id'] }}-tab"
                    x-show="activeTab === '{{ $tab['id'] }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                >
                    {{-- Render content. If content is an array, iterate over it; otherwise, display directly. --}}
                    @if (is_array($tab['content']))
                        @foreach ($tab['content'] as $item)
                            @if (is_array($item) && isset($item['view']))
                                {{-- If item is an array with a 'view' key, it's a Blade include --}}
                                @include($item['view'], $item['data'] ?? [])
                            @else
                                {{-- Otherwise, it's raw HTML --}}
                                {!! $item !!}
                            @endif
                        @endforeach
                    @else
                        {{-- If content is a single string, it's raw HTML --}}
                        {!! $tab['content'] !!}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif
